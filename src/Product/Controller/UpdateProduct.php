<?php
declare(strict_types=1);

namespace App\Product\Controller;

use App\Entity\Product;
use App\Product\FormType\UpdateProductType;
use App\Product\Repository\ProductRepository;
use App\Product\Value\ProductCode;
use App\Upload\Repository\ImageRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Twig\Environment as Twig;

final class UpdateProduct
{
    private Twig $templating;
    private ProductRepository $productRepository;
    private FormFactoryInterface $formFactory;
    private UrlGeneratorInterface $urlGenerator;
    private ValidatorInterface $validator;

    public function __construct(
        Twig $templating,
        ProductRepository $productRepository,
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $urlGenerator,
        ValidatorInterface $validator
    )
    {
        $this->templating = $templating;
        $this->productRepository = $productRepository;
        $this->formFactory = $formFactory;
        $this->urlGenerator = $urlGenerator;
        $this->validator = $validator;
    }

    public function handle(Request $request): Response
    {
        $code = ProductCode::fromString($request->get('code'));
        $product = $this->productRepository->getByCode($code);

        if (in_array('application/json', $request->getAcceptableContentTypes())) {
            return $this->handleJson($request->getContent(), $product);
        }

        $form = $this->formFactory->create(UpdateProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $this->productRepository->update(
                ProductCode::fromString($product->code),
                $product
            );
            return new RedirectResponse($this->urlGenerator->generate('list_products'));
        }

        return new Response(
            $this->templating->render(
                'product/edit.html.twig',
                ['form' => $form->createView(),]
            )
        );
    }

    private function handleJson(string $content, Product $product)
    {
        $requestData = json_decode($content, true);

        foreach (['name', 'price'] as $field) {
            if (isset($requestData[$field])) {
                $product->{$field} = $requestData[$field];
            }
        }

        $errors = $this->validator->validate($product);
        if (count($errors) > 0) {
            $mappedErrors = [];
            foreach ($errors as $error) {
                $mappedErrors[] = [
                    'property' => $error->getPropertyPath(),
                    'message' => $error->getMessage(),
                    'invalidValue' => $error->getInvalidValue(),
                ];
            }
            return new JsonResponse($mappedErrors);
        }

        $this->productRepository->update(
            ProductCode::fromString($product->code),
            $product
        );

        return new JsonResponse([
            'item' => $product,
        ]);
    }
}
