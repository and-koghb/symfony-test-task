<?php

namespace App\Tests\Service;

use App\Entity\Currency;
use App\Entity\Product;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use PHPUnit\Framework\TestCase;

class ProductServiceTest extends TestCase
{
    public function testGetProductWithCurrency()
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $expectedProduct = new Product();
        $expectedProduct->setName('Test Product');
        $expectedProduct->setPrice(10.99);

        $currency = new Currency();
        $currency->setCode('USD');
        $expectedProduct->setCurrency($currency);

        $query = $this->createMock(Query::class);
        $query->expects($this->once())
            ->method('getOneOrNullResult')
            ->willReturn($expectedProduct);

        $queryBuilder = $this->getMockBuilder('Doctrine\ORM\QueryBuilder')
            ->disableOriginalConstructor()
            ->getMock();
        $queryBuilder->expects($this->once())
            ->method('select')
            ->willReturnSelf();
        $queryBuilder->expects($this->once())
            ->method('from')
            ->willReturnSelf();
        $queryBuilder->expects($this->once())
            ->method('leftJoin')
            ->willReturnSelf();
        $queryBuilder->expects($this->once())
            ->method('where')
            ->willReturnSelf();
        $queryBuilder->expects($this->once())
            ->method('setParameter')
            ->willReturnSelf();
        $queryBuilder->expects($this->once())
            ->method('getQuery')
            ->willReturn($query);

        $entityManager->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($queryBuilder);

        $productService = new ProductService($entityManager);

        $productId = 1;
        $result = $productService->getProductWithCurrency($productId);

        $this->assertEquals($expectedProduct, $result);
    }
}
