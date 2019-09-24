@initDbBeforeFeature
@initDbAfterFeature
Feature: Compare

  Scenario: Compare password using correct password
    Given I am connected to uri "ldap://ldap-service"
    And I am bound with binddn "cn=admin,dc=example,dc=org" and password "admin"
    When I compare dn "uid=jsmith,ou=people,dc=example,dc=org", attribute "userpassword" with value "secret"
    Then I should see no exception
    And I should have last result true

  Scenario: Compare password using wrong password
    Given I am connected to uri "ldap://ldap-service"
    And I am bound with binddn "cn=admin,dc=example,dc=org" and password "admin"
    When I compare dn "uid=jsmith,ou=people,dc=example,dc=org", attribute "userpassword" with value "wrongpass"
    Then I should see no exception
    And I should have last result false

  Scenario: Compare with inexistent entry
    Given I am connected to uri "ldap://ldap-service"
    And I am bound with binddn "cn=admin,dc=example,dc=org" and password "admin"
    When I compare dn "uid=inexistent,ou=people,dc=example,dc=org", attribute "userpassword" with value "secret"
    Then I should see ldap exception with message "No such object"
