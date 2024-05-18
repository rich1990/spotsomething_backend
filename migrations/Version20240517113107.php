<?php

declare(strict_types=1);
namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240518123456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create flats table to allow Flat table ';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('flats');

        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['length' => 255, 'notnull' => true]);
        $table->addColumn('img', 'string', ['length' => 255, 'notnull' => false]);
        $table->addColumn('description', 'text', ['length' => 4294967295, 'notnull' => false]);
        $table->addColumn('city', 'string', ['length' => 100, 'notnull' => true]);

        $table->addIndex(['city'], 'idx_city');
        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        // Define how to revert the changes
        $schema->dropTable('flats');
    }
}
