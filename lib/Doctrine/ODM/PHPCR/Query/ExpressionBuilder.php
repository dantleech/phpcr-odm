<?php

namespace Doctrine\ODM\PHPCR\Query;

/**
 * This class is used to generate DQL expressions via a set of PHP static functions
 */
class ExpressionBuilder
{
    protected $qomf;

    public function __construct(QueryObjectModelFactoryInterface $qomf)
    {
        $this->qomf = $qomf;
    }

    /**
     * Creates a conjunction of the given boolean expressions.
     *
     * Example:
     *
     *     [php]
     *     $expr->andX($expr->eq('type', ':1'), $expr->eq('role', ':2'));
     *
     * @param $constraint1 ConstraintInterface First constraint
     * @param $constraint2 ConstraintInterface Second constraint
     * @return PHPCR\Query\QOM\AndInterface;
     */
    public function andX(ConstraintInterface $constraint1, ConstraintInterface $constraint2)
    {
        return $this->qomf->andConstraint($constraint1, $constraint2);
    }

    /**
     * Creates a disjunction of the given boolean expressions.
     *
     * Example:
     *
     *     [php]
     *     $expr->orX($expr->eq('type', ':1'), $expr->eq('role', ':2'));
     *
     * @param $constraint1 ConstraintInterface First constraint
     * @param $constraint2 ConstraintInterface Second constraint
     * @return PHPCR\Query\QOM\OrInterface;
     */
    public function orX(ConstraintInterface $constraint1, ConstraintInterface $constraint2)
    {
        return $this->qomf->orConstraint($constraint1, $constraint2);
    }

    /**
     * Creates an ASCending order expression.
     *
     * @param $sort DynamicOperandInterface
     * @return PHPCR\Query\QOM\OrderingInterface;
     */
    public function asc(DynamicOperandInterface $operand)
    {
        return $this->qomf->ascending($operand);
    }


    /**
     * Creates an DESCending order expression.
     *
     * @param $sort DynamicOperandInterface
     * @return PHPCR\Query\QOM\OrderingInterface;
     */
    public function desc(DynamicOperandInterface $operand)
    {
        return $this->qomf->descending($operand);
    }

    public function comparison($x, $operator, $y)
    {
        if (is_scalar($x)) {
            $x = $this->qomf->propertyValue($x);
        }

        if (is_scalar($y)) {
            $y = $this->qomf->literal($y);
        }

        return $this->qomf->comparison($x, $operator, $y);
    }

    /**
     * Creates an equality comparison expression with the given arguments.
     *
     * First argument is considered the left expression and the second is the right expression.
     * When converted to string, it will generated a <left expr> = <right expr>. Example:
     *
     *     [php]
     *     $expr->eq('field', 'value');
     *
     * @param mixed $x mixed Left expression
     * @param mixed $y mixed Right expression
     * @return 
     */
    public function eq($x, $y)
    {
        return $this->comparison($x, QOMConstants::JCR_OPERATOR_EQUAL_TO, $y);
    }

    /**
     *
     *     [php]
     *     $q->where($q->expr()->neq('id', 1));
     *
     * @param mixed $x Left expression
     * @param mixed $y Right expression
     * @return Expr\Comparison
     */
    public function neq($x, $y)
    {
        return $this->comparison($x, QOMConstants::JCR_OPERATOR_NOT_EQUAL_TO, $y);
    }

    /**
     * Creates an instance of Expr\Comparison, with the given arguments.
     * First argument is considered the left expression and the second is the right expression.
     * When converted to string, it will generated a <left expr> < <right expr>. Example:
     *
     *     [php]
     *     // u.id < ?1
     *     $q->where($q->expr()->lt('u.id', '?1'));
     *
     * @param mixed $x Left expression
     * @param mixed $y Right expression
     * @return Expr\Comparison
     */
    public function lt($x, $y)
    {
        return $this->comparison($x, QOMConstants::JCR_OPERATOR_LESS_THAN, $y);
    }

    /**
     * Creates an instance of Expr\Comparison, with the given arguments.
     * First argument is considered the left expression and the second is the right expression.
     * When converted to string, it will generated a <left expr> <= <right expr>. Example:
     *
     *     [php]
     *     // u.id <= ?1
     *     $q->where($q->expr()->lte('u.id', '?1'));
     *
     * @param mixed $x Left expression
     * @param mixed $y Right expression
     * @return Expr\Comparison
     */
    public function lte($x, $y)
    {
        return $this->comparison($x, QOMConstants::JCR_OPERATOR_LESS_THAN_OR_EQUAL_TO, $y);
    }

    /**
     * Creates an instance of Expr\Comparison, with the given arguments.
     * First argument is considered the left expression and the second is the right expression.
     * When converted to string, it will generated a <left expr> > <right expr>. Example:
     *
     *     [php]
     *     // u.id > ?1
     *     $q->where($q->expr()->gt('u.id', '?1'));
     *
     * @param mixed $x Left expression
     * @param mixed $y Right expression
     * @return Expr\Comparison
     */
    public function gt($x, $y)
    {
        return $this->comparison($x, QOMConstants::JCR_OPERATOR_GREATER_THAN, $y);
    }

    /**
     * Creates an instance of Expr\Comparison, with the given arguments.
     * First argument is considered the left expression and the second is the right expression.
     * When converted to string, it will generated a <left expr> >= <right expr>. Example:
     *
     *     [php]
     *     // u.id >= ?1
     *     $q->where($q->expr()->gte('u.id', '?1'));
     *
     * @param mixed $x Left expression
     * @param mixed $y Right expression
     * @return Expr\Comparison
     */
    public function gte($x, $y)
    {
        return $this->comparison($x, QOMConstants::JCR_OPERATOR_GREATER_THAN_OR_EQUAL_TO, $y);
    }

    /**
     * Creates a LOWER() function expression with the given argument.
     *
     * @param mixed $x Argument to be used in LOWER() function.
     * @return Expr\Func A LOWER function expression.
     */
    public function lower($x)
    {
        return $this->qomf->lower($this->scalarToPropertyValue($x));
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function propertyExistence($propertyName, $selectorName = null)
    {
        return $this->qomf->propertyExistence($selectorName, $propertyName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function fullTextSearch($propertyName, $fullTextSearchExpression, $selectorName = null)
    {
        return $this->qomf->fullTextSearch($propertyName, $fullTextSearchExpression, $selectorName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function sameNode($path, $selectorName = null)
    {
        return $this->qomf->sameNode($selectorName, $path);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function childNode($path, $selectorName = null)
    {
        return $this->qomf->childNodeConstraint($path, $selectorName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function descendantNode($path, $selectorName = null)
    {
        return $this->qomf->descendantNodeConstraint($path, $selectorName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function propertyValue($propertyName, $selectorName = null)
    {
        return $this->qomf->propertyValue($selectorName, $propertyName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function length(PropertyValueInterface $propertyValue)
    {
        return $this->qomf->length($propertyValue);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function nodeName($selectorName = null)
    {
        return $this->qomf->nodeName($selectorName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function nodeLocalName($selectorName = null)
    {
        return $this->qomf->nodeLocalName($selectorName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function fullTextSearchScore($selectorName = null)
    {
        return $this->qomf->fullTextSearchScore($selectorName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function lowerCase(DynamicOperandInterface $operand)
    {
        return $this->qomf->lowerCase($operand);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function upperCase(DynamicOperandInterface $operand)
    {
        return $this->qomf->upperCase($operand);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function bindVariable($bindVariableName)
    {
        return $this->qomf->bindVariable($bindVariableName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function column($propertyName, $columnName = null, $selectorName = null)
    {
        return $this->qomf->column($propertyName, $columnName, $selectorName);
    }

    /**
     * Creates an instance of AVG() function, with the given argument.
     *
     * @param mixed $x Argument to be used in AVG() function.
     * @return Expr\Func
     */
    public function avg($x)
    {
        throw new \Exception('Can we support this?');
    }

    /**
     * Creates an instance of MAX() function, with the given argument.
     *
     * @param mixed $x Argument to be used in MAX() function.
     * @return Expr\Func
     */
    public function max($x)
    {
        throw new \Exception('Can we support this?');
    }

    /**
     * Creates an instance of MIN() function, with the given argument.
     *
     * @param mixed $x Argument to be used in MIN() function.
     * @return Expr\Func
     */
    public function min($x)
    {
        throw new \Exception('Can we support this?');
    }

    /**
     * Creates an instance of COUNT() function, with the given argument.
     *
     * @param mixed $x Argument to be used in COUNT() function.
     * @return Expr\Func
     */
    public function count($x)
    {
        throw new \Exception('Can we support this?');
    }

    /**
     * Creates an instance of COUNT(DISTINCT) function, with the given argument.
     *
     * @param mixed $x Argument to be used in COUNT(DISTINCT) function.
     * @return string
     */
    public function countDistinct($x)
    {
        throw new \Exception('Can we support this?');
    }

    /**
     * Creates an instance of EXISTS() function, with the given DQL Subquery.
     *
     * @param mixed $subquery DQL Subquery to be used in EXISTS() function.
     * @return Expr\Func
     */
    public function exists($subquery)
    {
        throw new \Exception('Can we support this?');
    }

    /**
     * Creates an instance of ALL() function, with the given DQL Subquery.
     *
     * @param mixed $subquery DQL Subquery to be used in ALL() function.
     * @return Expr\Func
     */
    public function all($subquery)
    {
        throw new \Exception('Can we support this?');
    }

    /**
     * Creates a SOME() function expression with the given DQL subquery.
     *
     * @param mixed $subquery DQL Subquery to be used in SOME() function.
     * @return Expr\Func
     */
    public function some($subquery)
    {
        throw new \Exception('Can we support this?');
    }

    /**
     * Creates an ANY() function expression with the given DQL subquery.
     *
     * @param mixed $subquery DQL Subquery to be used in ANY() function.
     * @return Expr\Func
     */
    public function any($subquery)
    {
        throw new \Exception('Can we support this?');
    }

    /**
     * Creates a negation expression of the given restriction.
     *
     * @param mixed $restriction Restriction to be used in NOT() function.
     * @return Expr\Func
     */
    public function not($restriction)
    {
        return new Expr\Func('NOT', array($restriction));
    }

    /**
     * Creates an ABS() function expression with the given argument.
     *
     * @param mixed $x Argument to be used in ABS() function.
     * @return Expr\Func
     */
    public function abs($x)
    {
        throw new \Exception('Can we support this?');
    }

    /**
     * Creates a product mathematical expression with the given arguments.
     *
     * First argument is considered the left expression and the second is the right expression.
     * When converted to string, it will generated a <left expr> * <right expr>. Example:
     *
     *     [php]
     *     // u.salary * u.percentAnualSalaryIncrease
     *     $q->expr()->prod('u.salary', 'u.percentAnualSalaryIncrease')
     *
     * @param mixed $x Left expression
     * @param mixed $y Right expression
     * @return Expr\Math
     */
    public function prod($x, $y)
    {
        throw new \Exception('Can we support this?');
    }

    /**
     * Creates a difference mathematical expression with the given arguments.
     * First argument is considered the left expression and the second is the right expression.
     * When converted to string, it will generated a <left expr> - <right expr>. Example:
     *
     *     [php]
     *     // u.monthlySubscriptionCount - 1
     *     $q->expr()->diff('u.monthlySubscriptionCount', '1')
     *
     * @param mixed $x Left expression
     * @param mixed $y Right expression
     * @return Expr\Math
     */
    public function diff($x, $y)
    {
        throw new \Exception('Can we support this?');
    }

    /**
     * Creates a sum mathematical expression with the given arguments.
     * First argument is considered the left expression and the second is the right expression.
     * When converted to string, it will generated a <left expr> + <right expr>. Example:
     *
     *     [php]
     *     // u.numChildren + 1
     *     $q->expr()->diff('u.numChildren', '1')
     *
     * @param mixed $x Left expression
     * @param mixed $y Right expression
     * @return Expr\Math
     */
    public function sum($x, $y)
    {
        throw new \Exception('Can we support this?');
    }

    /**
     * Creates a quotient mathematical expression with the given arguments.
     * First argument is considered the left expression and the second is the right expression.
     * When converted to string, it will generated a <left expr> / <right expr>. Example:
     *
     *     [php]
     *     // u.total / u.period
     *     $expr->quot('u.total', 'u.period')
     *
     * @param mixed $x Left expression
     * @param mixed $y Right expression
     * @return Expr\Math
     */
    public function quot($x, $y)
    {
        throw new \Exception('Can we support this?');
    }

    /**
     * Creates a SQRT() function expression with the given argument.
     *
     * @param mixed $x Argument to be used in SQRT() function.
     * @return Expr\Func
     */
    public function sqrt($x)
    {
        throw new \Exception('Can we support this?');
    }

    /**
     * Creates an IN() expression with the given arguments.
     *
     * @param string $x Field in string format to be restricted by IN() function
     * @param mixed $y Argument to be used in IN() function.
     * @return Expr\Func
     */
    public function in($x, $y)
    {
        throw new \Exception('Can we support this?');
    }

    /**
     * Creates a NOT IN() expression with the given arguments.
     *
     * @param string $x Field in string format to be restricted by NOT IN() function
     * @param mixed $y Argument to be used in NOT IN() function.
     * @return Expr\Func
     */
    public function notIn($x, $y)
    {
        throw new \Exception('Can we support this?');
    }

    /**
     * Creates an IS NULL expression with the given arguments.
     *
     * @param string $x Field in string format to be restricted by IS NULL
     * @return string
     */
    public function isNull($x)
    {
        throw new \Exception('Can we support this?');
    }

    /**
     * Creates an IS NOT NULL expression with the given arguments.
     *
     * @param string $x Field in string format to be restricted by IS NOT NULL
     * @return string
     */
    public function isNotNull($x)
    {
        throw new \Exception('Can we support this?');
    }

    /**
     * Creates a LIKE() comparison expression with the given arguments.
     *
     * @param string $x Field in string format to be inspected by LIKE() comparison.
     * @param mixed $y Argument to be used in LIKE() comparison.
     * @return Expr\Comparison
     */
    public function like($x, $y)
    {
        throw new \Exception('Can we support this?');
    }

    /**
     * Creates a CONCAT() function expression with the given arguments.
     *
     * @param mixed $x First argument to be used in CONCAT() function.
     * @param mixed $x Second argument to be used in CONCAT() function.
     * @return Expr\Func
     */
    public function concat($x, $y)
    {
        throw new \Exception('Can we support this?');
    }

    /**
     * Creates a SUBSTRING() function expression with the given arguments.
     *
     * @param mixed $x Argument to be used as string to be cropped by SUBSTRING() function.
     * @param integer $from Initial offset to start cropping string. May accept negative values.
     * @param integer $len Length of crop. May accept negative values.
     * @return Expr\Func
     */
    public function substring($x, $from, $len = null)
    {
        throw new \Exception('Can we support this?');
    }

    /**
     * Creates an UPPER() function expression with the given argument.
     *
     * @param mixed $x Argument to be used in UPPER() function.
     * @return Expr\Func An UPPER function expression.
     */
    public function upper($x)
    {
        throw new \Exception('Can we support this?');
    }

    /**
     * Creates a LENGTH() function expression with the given argument.
     *
     * @param mixed $x Argument to be used as argument of LENGTH() function.
     * @return Expr\Func A LENGTH function expression.
     */
    public function length($x)
    {
        throw new \Exception('Can we support this?');
    }

    /**
     * Creates a literal expression of the given argument.
     *
     * @param mixed $literal Argument to be converted to literal.
     * @return Expr\Literal
     */
    public function literal($literal)
    {
        return $this->qomf->literal($literal);
    }

    /**
     * Quotes a literal value, if necessary, according to the DQL syntax.
     *
     * @param mixed $literal The literal value.
     * @return string
     */
    private function _quoteLiteral($literal)
    {
        throw new \Exception('Can we support this?');
    }

    /**
     * Creates an instance of BETWEEN() function, with the given argument.
     *
     * @param mixed $val Valued to be inspected by range values.
     * @param integer $x Starting range value to be used in BETWEEN() function.
     * @param integer $y End point value to be used in BETWEEN() function.
     * @return Expr\Func A BETWEEN expression.
     */
    public function between($val, $x, $y)
    {
        throw new \Exception('Can we support this?');
    }

    /**
     * Creates an instance of TRIM() function, with the given argument.
     *
     * @param mixed $x Argument to be used as argument of TRIM() function.
     * @return Expr\Func a TRIM expression.
     */
    public function trim($x)
    {
        throw new \Exception('Can we support this?');
    }
}
