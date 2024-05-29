<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240527131553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create currencies table';
    }

    public function up(Schema $schema): void
    {
        if (!$schema->hasTable('currencies')) {
            $table = $schema->createTable('currencies');

            $table->addColumn('id', 'integer', ['autoincrement' => true]);
            $table->addColumn('name', 'string', ['length' => 125]);
            $table->addColumn('code', 'string', ['length' => 15]);
            $table->addColumn('sub_unit', 'string', ['length' => 25, 'notnull' => false]);
            $table->addColumn('decimals', 'smallint', ['notnull' => false]);
            $table->addColumn('symbol', 'string', ['length' => 15, 'notnull' => false]);
            $table->addColumn('type', 'smallint');
            $table->addColumn('status', 'smallint');
            $table->addColumn('created_at', 'datetime', ['notnull' => false]);
            $table->addColumn('updated_at', 'datetime', ['notnull' => false]);

            $table->setPrimaryKey(['id']);
            $table->addUniqueIndex(['name']);
            $table->addUniqueIndex(['code']);
            $table->addIndex(['type']);
            $table->addIndex(['status']);
        }
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('currencies')) {
            $schema->dropTable('currencies');
        }
    }
}
