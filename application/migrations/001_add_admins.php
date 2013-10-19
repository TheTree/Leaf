<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_admin extends CI_Migration {

    public function up() {
        $this->dbforge
            ->add_field(array(
                            'id'            => array(
                                'type'           => 'TINYINT',
                                'constraint'     => 3,
                                'unsigned'       => TRUE,
                                'auto_increment' => TRUE
                            ),
                            'username'      => array(
                                'type'       => 'VARCHAR',
                                'constraint' => '32',
                            ),
                            'seo_username'  => array(
                                'type'       => 'VARCHAR',
                                'constraint' => '32',
                            ),
                            'password_hash' => array(
                                'type'       => 'VARCHAR',
                                'constraint' => '255'
                            ),
                            'salt'          => array(
                                'type'       => 'VARCHAR',
                                'constraint' => '5'
                            )
                        ));

        $this->dbforge->create_table('ci_admins');
    }

    public function down() {
        $this->dbforge->drop_table('ci_admins');
    }
}