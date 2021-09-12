Feature: User
  In order to manage users,

  Scenario: Access user management pages
    Given I am an admin
    When I try to access a user's management page
    Then this access is granted

    Given I am an user
    When I try to access a user's management page
    Then this access is not granted