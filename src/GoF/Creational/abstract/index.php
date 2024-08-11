<?php

namespace RefactoringGuru\AbstractFactory\Conceptual;

/**
 * A interface Abstract Factory declara um conjunto de métodos que retornam
 * produtos abstratos diferentes. Esses produtos são chamados de família sendo
 * relacionados por um tema ou conceito de alto nível. Os produtos de uma família são geralmente
 * capazes de colaborar entre si. Uma família de produtos pode ter vários
 * variantes, mas os produtos de uma variante são incompatíveis com produtos de
 * outro.
 */
interface AbstractFactory
{
    public function createProductA(): AbstractProductA;

    public function createProductB(): AbstractProductB;
}

/**
 * As fábricas concretas produzem uma família de produtos que pertencem a um único
 * variante. A fábrica garante que os produtos resultantes são compatíveis. Nota
 * que as assinaturas dos métodos da Fábrica Concreta retornam um produto abstrato,
 * enquanto dentro do método, um produto concreto é instanciado.
 */
class ConcreteFactory1 implements AbstractFactory
{

    public function createProductA(): AbstractProductA
    {
        return new ConcreteProductA1();
    }

    public function createProductB(): AbstractProductB
    {
        return new ConcreteProductB1();
    }
}

/**
 * Cada fábrica concreta tem uma variante de produto correspondente.
 */
class ConcreteFactory2 implements AbstractFactory
{

    public function createProductA(): AbstractProductA
    {
        return new ConcreteProductA2();
    }

    public function createProductB(): AbstractProductB
    {
        return new ConcreteProductB2();
    }
}

/**
 * Cada produto distinto de uma família de produtos deve ter uma interface básica. Todas
 * as variantes do produto devem implementar essa interface.
 */
interface AbstractProductA
{
    public function usefulFunctionA(): string;
}

/**
 * Os produtos concretos são criados pelas fábricas concretas correspondentes.
 */
class ConcreteProductA1 implements AbstractProductA
{

    public function usefulFunctionA(): string
    {
        return "The result of the product A1.";
    }
}

class ConcreteProductA2 implements AbstractProductA
{

    public function usefulFunctionA(): string
    {
        return "The result of the product A2.";
    }
}

/**
 * Aqui está a interface base de outro produto. Todos os produtos podem interagir
 * entre si, mas a interação adequada só é possível entre produtos da mesma variante concreta.
 */
interface AbstractProductB
{
    /**
     * O Produto B consegue fazer suas próprias coisas
     */
    public function usefulFunctionB(): string;

    /**
     * Mas também pode colaborar com o ProductA.
     *
     * A Fábrica Abstrata garante que todos os produtos que cria sejam da
     * mesma variante e, portanto, compatíveis.
     */
    public function anotherUsefulFunctionB(AbstractProductA $collaborator): string;
}

/**
 * Produtos concretos são criados pelas fábricas concretas correspondentes
 */
class ConcreteProductB1 implements AbstractProductB
{

    public function usefulFunctionB(): string
    {
        return "The result of the product B1.";
    }

    /**
     * A variante, Produto B1, só pode funcionar corretamente com a variante,
     * Produto A1. No entanto, ela aceita qualquer instância de AbstractProductA como
     * um argumento.
     */
    public function anotherUsefulFunctionB(AbstractProductA $collaborator): string
    {
        $result = $collaborator->usefulFunctionA();
        return "The result of the B1 collaborating with the ({$result})";
    }
}

class ConcreteProductB2 implements AbstractProductB
{

    public function usefulFunctionB(): string
    {
        return "The result of the product B2.";
    }

    /**
     * A variante, Produto B2, só pode funcionar corretamente com a variante,
     * Produto A2. No entanto, ela aceita qualquer instância de AbstractProductA como
     * um argumento.
     */
    public function anotherUsefulFunctionB(AbstractProductA $collaborator): string
    {
        $result = $collaborator->usefulFunctionA();
        return "The result of the B2 collaborating with the ({$result})";
    }
}

/**
 * O código do cliente funciona com fábricas e produtos apenas através de abstrato
 * tipos: AbstractFactory e AbstractProduct. Isso permite que você passe por qualquer fábrica ou
 * subclasse do produto ao código do cliente sem quebrá-lo.
 */
function clientCode(AbstractFactory $factory)
{
    $productA = $factory->createProductA();
    $productB = $factory->createProductB();

    echo $productB->usefulFunctionB() . PHP_EOL;
    echo $productB->anotherUsefulFunctionB($productA) . PHP_EOL;
}

/**
 * O código do cliente pode funcionar com qualquer classe de fábrica concreta.
 */
echo "Client: Testing client code with the first factory type:\n";
clientCode(new ConcreteFactory1());

echo PHP_EOL;

echo "Client: Testing the same client code with the second factory type: \n";
clientCode(new ConcreteFactory2());