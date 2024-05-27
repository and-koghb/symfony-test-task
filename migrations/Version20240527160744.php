<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240527160744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create products table';
    }

    public function up(Schema $schema): void
    {
        if (!$schema->hasTable('products')) {
            $table = $schema->createTable('products');
            $table->addColumn('id', 'bigint', ['autoincrement' => true]);
            $table->addColumn('name', 'string', ['length' => 255]);
            $table->addColumn('price', 'decimal', ['precision' => 12, 'scale' => 2]);
            $table->addColumn('currency_id', 'integer', ['notnull' => false]);
            $table->addColumn('user_id', 'bigint');
            $table->addColumn('status', 'smallint');
            $table->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP']);
            $table->addColumn('updated_at', 'datetime', ['notnull' => false]);
            $table->setPrimaryKey(['id']);
            $table->addIndex(['name']);
            $table->addIndex(['price']);
            $table->addIndex(['status']);
            $table->addForeignKeyConstraint(
                'currencies',
                ['currency_id'],
                ['id'],
                ['onUpdate' => 'CASCADE', 'onDelete' => 'SET NULL']
            );
            $table->addForeignKeyConstraint(
                'users',
                ['user_id'],
                ['id'],
                ['onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE']
            );
        }
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('products')) {
            $schema->dropTable('products');
        }
    }
}
