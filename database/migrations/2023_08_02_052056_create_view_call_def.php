<?php

use Illuminate\Database\Migrations\Migration;
use App\Traits\Connections;

return new class extends Migration {

   use Connections;

   /**
    * Run the migrations.
    */
   public function up(): void {

      DB::connection(Connections::calls_connection())->statement('CREATE OR REPLACE VIEW v_call_def AS
         select
             `d`.`id` AS `definition_id`,
             `d`.`program_id` AS `program_id`,
             `p`.`name` AS `program_name`,
             `d`.`call_id` AS `call_id`,
             `c`.`name` AS `call_name`,
             `d`.`start_end_formation_id` AS `start_end_formation_id`,
             `sef`.`start_formation_id` AS `start_formation_id`,
             `sef`.`end_formation_id` AS `end_formation_id`,
             `sf`.`name` AS `start_formation_name`,
             `ef`.`name` AS `end_formation_name`,
             `df`.`id` AS `definition_fragments_id`,
             `df`.`fragment_id` AS `fragment_id`,
             `df`.`seq_no` AS `seq_no`,
             `calls_test`.`df`.`fragment_type_id` AS `type_id`,
             `calls_test`.`f`.`text` AS `text`
         from
             (((((((`calls_test`.`definition` `d`
         left join `calls_test`.`program` `p` on
             ((`calls_test`.`p`.`id` = `calls_test`.`d`.`program_id`)))
         left join `calls_test`.`sd_call` `c` on
             ((`calls_test`.`c`.`id` = `calls_test`.`d`.`call_id`)))
         left join `calls_test`.`start_end_formation` `sef` on
             ((`calls_test`.`sef`.`id` = `calls_test`.`d`.`start_end_formation_id`)))
         left join `calls_test`.`formation` `sf` on
             ((`calls_test`.`sf`.`id` = `calls_test`.`sef`.`start_formation_id`)))
         left join `calls_test`.`formation` `ef` on
             ((`calls_test`.`ef`.`id` = `calls_test`.`sef`.`end_formation_id`)))
         left join `calls_test`.`definition_fragments` `df` on
             ((`calls_test`.`df`.`definition_id` = `calls_test`.`d`.`id`)))
         left join `calls_test`.`fragment` `f` on
             ((`calls_test`.`f`.`id` = `calls_test`.`df`.`fragment_id`)))'
      );
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void {
      //
   }
};
