<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%links}}`.
 */
class m230607_235827_create_links_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('links', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'url' => $this->string(500)->notNull(),
            'expiredAt' => $this->dateTime()->notNull(),
        ]);

        $this->createIndex('idx-expiredAt',
            'links',
            ['expiredAt'],
            false
        );

        $this->createIndex('idx-id-expiredAt',
            'links',
            ['id', 'expiredAt'],
            false
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-expiredAt', 'links');
        $this->dropIndex('idx-id-state', 'links');
        $this->dropTable('links');
    }
}
