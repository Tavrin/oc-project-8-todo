Feature: task
  In order to manage tasks,

  Scenario: Connect tasks to their associated users
    Given I am an authenticated user
    When I create a task
    Then I should be associated to the task as its author