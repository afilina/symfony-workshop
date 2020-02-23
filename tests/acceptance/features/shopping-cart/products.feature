Feature:
  Scenario: I can add products to a cart
    Given There is a product "Product 1"
    And I am viewing product "Product 1"
    When I add the product to my cart
    Then I should see 1 product in my cart

  Scenario: Subsequently adding a product adds to existing quantity
    Given There is a product "Product 1"
    And I am viewing product "Product 1"
    And I add the product to my cart
    And I am viewing product "Product 1"
    When I add the product to my cart
    Then I should see that the quantity of "Product 1" is 2
