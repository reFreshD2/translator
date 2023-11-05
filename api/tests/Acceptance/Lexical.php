<?php

declare(strict_types=1);

namespace Tests\Acceptance;

use Codeception\Actor;
use Tests\Support\_generated\AcceptanceTesterActions;

class Lexical extends Actor
{
    private const CODE = 'var a:integer;\nbegin\nreadln(a);\na*=2;\nwriteln(a);\nend.';
    private const TOKENS = <<<EOD
    (keyword, `var`)
    (id, `a`)
    (separator, `:`)
    (keyword, `integer`)
    (separator, `;`)
    (keyword, `begin`)
    (keyword, `readln`)
    (bracket, `(`)
    (id, `a`)
    (bracket, `)`)
    (separator, `;`)
    (id, `a`)
    (assigment, `*=`)
    (int, `2`)
    (separator, `;`)
    (keyword, `writeln`)
    (bracket, `(`)
    (id, `a`)
    (bracket, `)`)
    (separator, `;`)
    (keyword, `end`)
    (separator, `.`)
    EOD;

    use AcceptanceTesterActions;

    /**
     * @Given I write code for lexical analyze
     */
    public function iWriteCodeForLexicalAnalyze(): void
    {
        $this->amOnPage('/lexical');
        $this->fillField('code', self::CODE);
        $this->selectOption('language', 'Pascal');
    }

    /**
     * @When I submit code for lexical analyze
     */
    public function iSubmitCodeForLexicalAnalyze(): void
    {
        $this->click('submit');
    }

    /**
     * @Then I should to receive a set of tokens from lexical analyze of the code
     */
    public function iShouldToRecieveASetOfTokensFromLexicalAnalyzeOfTheCode(): void
    {
        $this->see(self::TOKENS);
    }
}
