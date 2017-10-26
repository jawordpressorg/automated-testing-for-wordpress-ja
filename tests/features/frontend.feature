Feature: For front-end testing
  @mink:goutte
  Scenario: `homepage` returns status code 200
    Given I am on homepage
    Then the response status code should be 200
    
