Feature:
  @wip
  Scenario: All products appear in the list
    Given There is a product "Product 1"
    And There is a product "Product 2"
    When I list the products
    Then I should see 2 products
