<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpFoundation\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\ExpressionRequestMatcher;
use Symfony\Component\HttpFoundation\Request;

class ExpressionRequestMatcherTest extends TestCase
{
    /**
     * @expectedException \LogicException
     */
    public function testWhenNoExpressionIsSet() {
        $expressionRequestMatcher = new ExpressionRequestMatcher();
        $expressionRequestMatcher->matches(new Request());
    }

    /**
     * @dataProvider provideExpressions
     */
    public function testMatchesWhenParentMatchesIsTrue($expression, $expected) {
        $request = Request::create('/foo');
        $expressionRequestMatcher = new ExpressionRequestMatcher();

        $expressionRequestMatcher->setExpression(new ExpressionLanguage(), $expression);
        $this->assertSame($expected, $expressionRequestMatcher->matches($request));
    }

    /**
     * @dataProvider provideExpressions
     */
    public function testMatchesWhenParentMatchesIsFalse($expression) {
        $request = Request::create('/foo');
        $request->attributes->set('foo', 'foo');
        $expressionRequestMatcher = new ExpressionRequestMatcher();
        $expressionRequestMatcher->matchAttribute('foo', 'bar');

        $expressionRequestMatcher->setExpression(new ExpressionLanguage(), $expression);
        $this->assertFalse($expressionRequestMatcher->matches($request));
    }

    public function provideExpressions() {
        return array(
            array('request.getMethod() == method', TRUE),
            array('request.getPathInfo() == path', TRUE),
            array('request.getHost() == host', TRUE),
            array('request.getClientIp() == ip', TRUE),
            array('request.attributes.all() == attributes', TRUE),
            array('request.getMethod() == method && request.getPathInfo() == path && request.getHost() == host && request.getClientIp() == ip &&  request.attributes.all() == attributes', TRUE),
            array('request.getMethod() != method', FALSE),
            array('request.getMethod() != method && request.getPathInfo() == path && request.getHost() == host && request.getClientIp() == ip &&  request.attributes.all() == attributes', FALSE),
        );
    }
}
