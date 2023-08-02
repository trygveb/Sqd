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
             `calls`.`df`.`fragment_type_id` AS `type_id`,
             `calls`.`f`.`text` AS `text`
         from
             (((((((`calls`.`definition` `d`
         left join `calls`.`program` `p` on
             ((`calls`.`p`.`id` = `calls`.`d`.`program_id`)))
         left join `calls`.`sd_call` `c` on
             ((`calls`.`c`.`id` = `calls`.`d`.`call_id`)))
         left join `calls`.`start_end_formation` `sef` on
             ((`calls`.`sef`.`id` = `calls`.`d`.`start_end_formation_id`)))
         left join `calls`.`formation` `sf` on
             ((`calls`.`sf`.`id` = `calls`.`sef`.`start_formation_id`)))
         left join `calls`.`formation` `ef` on
             ((`calls`.`ef`.`id` = `calls`.`sef`.`end_formation_id`)))
         left join `calls`.`definition_fragments` `df` on
             ((`calls`.`df`.`definition_id` = `calls`.`d`.`id`)))
         left join `calls`.`fragment` `f` on
             ((`calls`.`f`.`id` = `calls`.`df`.`fragment_id`)))'
      );
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void {
      //
   }
};
