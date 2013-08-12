Feature: Do a Google search
  In order to find my blog posts
  As a user
  I want to be able to use google.com to locate search results

  @javascript
  Scenario: I want to search for a blog post
    Given I am on "/"
    And I fill in "q" with "Exploring SOAP in Zend Framework 2"
    And I wait
    When I press "Google Keresés"
    Then I should see "shanethehat"
