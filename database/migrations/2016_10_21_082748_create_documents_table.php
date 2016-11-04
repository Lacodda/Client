<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateDocumentsTable
        extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up ()
        {
            Schema::create (
                'documents',
                function (Blueprint $table)
                {
                    $table->increments ('id');
                    $table->timestamps ();
                    $table->integer ('client_id');
                    $table->string ('invoice_number');
                    $table->date ('invoice_date');
                    $table->string ('act_number');
                    $table->date ('act_date');
                    $table->decimal ('amount', 8, 2);
                    $table->decimal ('vat', 8, 2);
                    $table->boolean ('final');
                }
            );
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down ()
        {
            Schema::drop ('documents');
        }
    }
