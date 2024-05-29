<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240527150039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create users table';
    }

    public function up(Schema $schema): void
    {
        if (!$schema->hasTable('users')) {
            $table = $schema->createTable('users');

            $table->addColumn('id', 'bigint', ['autoincrement' => true]);
            $table->addColumn('firstname', 'string', ['length' => 255, 'notnull' => false]);
            $table->addColumn('lastname', 'string', ['length' => 255, 'notnull' => false]);
            $table->addColumn('email', 'string', ['length' => 255]);
            $table->addColumn('password', 'string', ['length' => 255]);
            $table->addColumn('remember_token', 'string', ['length' => 255, 'notnull' => false]);
            $table->addColumn('email_verified_at', 'datetime', ['notnull' => false]);
            $table->addColumn('main_currency_id', 'integer', ['notnull' => false]);
            $table->addColumn('status', 'smallint');
            $table->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP']);
            $table->addColumn('updated_at', 'datetime', ['notnull' => false]);

            $table->setPrimaryKey(['id']);
            $table->addIndex(['firstname']);
            $table->addIndex(['lastname']);
            $table->addUniqueIndex(['email']);
            $table->addIndex(['email_verified_at']);
            $table->addIndex(['status']);
            $table->addForeignKeyConstraint(
                'currencies',
                ['main_currency_id'],
                ['id'],
                ['onUpdate' => 'CASCADE', 'onDelete' => 'SET NULL']
            );
        }
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('users')) {
            $schema->dropTable('users');
        }
    }
}
