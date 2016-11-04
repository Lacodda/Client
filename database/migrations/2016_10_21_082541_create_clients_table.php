<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateClientsTable
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
                'clients',
                function (Blueprint $table)
                {
                    $table->increments ('id');
                    $table->timestamps ();
                    $table->string ('alias');
                    $table->string ('name');
                    $table->string ('inn');
                    $table->string ('kpp');
                    $table->text ('address');
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
            Schema::drop ('clients');
        }
    }
