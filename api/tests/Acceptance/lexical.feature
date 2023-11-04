Feature: lexical
  In order to make lexical analyze
  As a user
  I need to be able to submit code for lexical analyze and receive a set of tokens

  Scenario: make lexical analyze
      Given I write code for lexical analyze
      When I submit code for lexical analyze
      Then I should to receive a set of tokens from lexical analyze of the code
