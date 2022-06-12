<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Http\Requests\PetsAddRequest;
use App\Http\Requests\PetsUpdateRequest;
use File;
use Carbon\Carbon;

class PetsController extends Controller
{
    public function listPets(){
            
        $pets = DB::select('select * from tb_pets');

        return view('pets')->with('pets', $pets);//->with('info', $info);
    }

    public function inspectPet(Request $request){

        $id=$request->route('id');

        $pet = DB::select('select * from tb_pets where id=?', array($id));

        

        return view('alterar_pet')->with('pet', $pet);
    }

    public function deletePet(Request $request){
        $id = $request->route('id');

        $delete = DB::delete('delete from tb_pets where id=?', array($id));

        $info = "";
        if($delete){
            $info="deleted_pet";
        }
        else{
            $info="error_delete_pet";
        }

        return redirect()->action([PetsController::class, 'listPets'])->with('info', $info);
    }

    public function updatePet(PetsUpdateRequest $request){

        $id = $request->post('txtCod');
        $nome = $request->post('txtNome');
        $idade = $request->post('txtIdade');
        $raca = $request->post('txtRaca');
        $raca_pai = $request->post('txtRacaP');
        $raca_mae = $request->post('txtRacaM');
        $saude = $request->post('txtSaude');
        $vacinas = $request->post('txtVacinas');
        $porte = $request->post('txtPorte');
        $genero = $request->post('txtGenero');

        $update = DB::update('update tb_pets
        set nome = ?,
        idade = ?,
        raca = ?,
        raca_pai = ?, 
        raca_mae = ?,
        saude = ?,
        vacinas_essenciais = ?,
        porte = ?,
        genero = ?
        where id=? ', array($nome, $idade, $raca, $raca_pai, $raca_mae, $saude, $vacinas,
        $porte, $genero, $id));

        $op_status ="";
        if($update){
            $op_status = "update_sucess";
        }
        else{
            $op_status = "update_fail";
        }

        return redirect([PetsController::class, 'listPets'])->with('op_info', $op_status);
    
    }

    public function insertPet(PetsAddRequest $request){
        $nome = $request->post('txtNome');
        $idade = $request->post('txtIdade');
        $raca = $request->post('txtRaca');
        $raca_pai = $request->post('txtRacaP');
        $raca_mae = $request->post('txtRacaM');
        $saude = $request->post('txtSaude');
        $vacinas = $request->post('txtVacinas');
        $porte = $request->post('txtPorte');
        $genero = $request->post('txtGenero');
        $foto = $request->file('inpFoto');
        (new PetsController)->getHash($foto);

        $insert = DB::insert('insert into tb_pets(nome, idade, raca, raca_pai,
        raca_mae, saude, vacinas_essenciais, porte, genero) values(
            ?, ?, ?, ?, ?, ?, ?, ?, ?
        )', array($nome, $idade, $raca, $raca_pai, $raca_mae, $saude, $vacinas,
                $porte, $genero
        ));

        $info="";
        if($insert){
            $info = "inserted_pet";
        }
        else{
            $info = "error_inserting_pet";
        }

        return redirect()
            ->action([PetsController::class, 'listPets'])
            ->with('info', $info);
    }



    //Operations to app (ajax) and webserver requests:

    public function getHash($file){
        
        /*
        $filename=$file->getClientOriginalName();
        $file->storeAs('/', $filename, 'public');
        $file->storeAs('tmp', $filename, 'public');

        File::delete('tmp/'.$filename);


        */
        $current_timestamp  = Carbon::now()->timestamp;
        $filename = hash('sha256', 'tmp'.$file->getClientOriginalName().$file->getClientOriginalExtension().
        $file->getSize().$current_timestamp).".".$file->getClientOriginalExtension();

        $tmp_dir = hash('sha256', 'tmp'.$file->getClientOriginalName().$file->getClientOriginalExtension().
        $file->getSize().$current_timestamp.'30/07/2003');

        $file->storeAs($tmp_dir, $filename, 'public');

        $main_img_name = hash_file('sha256', storage_path('app/public/'.$tmp_dir."/".$filename));

        $file->storeAs('/', $main_img_name, 'public');

        
        //Alternatives to delete files:
        
        /*
        unlink(realpath(storage_path('app/public/'.$tmp_dir.'/'.$filename)));
        rmdir(realpath(storage_path('app/public/'.$tmp_dir)));
        */


        /*
        unlink(storage_path('app/public/'.$tmp_dir.'/'.$filename));
        */

        File::delete(storage_path('app/public/'.$tmp_dir.'/'.$filename));
        
        rmdir(storage_path('app/public/'.$tmp_dir));
        
        

    }
    
}
