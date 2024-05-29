<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240529131954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates coupons table';
    }

    public function up(Schema $schema): void
    {
        if (!$schema->hasTable('coupons')) {
            $table = $schema->createTable('coupons');

            $table->addColumn('id', 'integer', ['autoincrement' => true]);
            $table->addColumn('code', 'string', ['length' => 55]);
            $table->addColumn('percent', 'smallint');
            $table->addColumn('status', 'smallint');
            $table->addColumn('user_id', 'integer', ['notnull' => false]);
            $table->addColumn('created_at', 'datetime', ['notnull' => false]);
            $table->addColumn('updated_at', 'datetime', ['notnull' => false]);

            $table->setPrimaryKey(['id']);
            $table->addIndex(['code']);
            $table->addIndex(['status']);
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
        if ($schema->hasTable('coupons')) {
            $schema->dropTable('coupons');
        }
    }
}
