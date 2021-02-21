<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Income;

class Incomes extends Component
{
    public $incomes, $description, $amount, $type;
    public $isModal = 0;

  	//LOAD VIEW
    public function render()
    {
        $this->incomes = Income::orderBy('created_at', 'DESC')->get(); //CREATE QUERY FOR GETDATA
        return view('livewire.incomes'); //LOAD VIEW INCOME.BLADE.PHP INSIDE /RESOURSCES/VIEWS/LIVEWIRE
    }

    //Start as add income button clicked
    public function create()
    {
        //Clear field
        $this->resetFields();
        //Open Modal
        $this->openModal();
    }

    //Close modal set to false
    public function closeModal()
    {
        $this->isModal = false;
    }

    //Open modal 
    public function openModal()
    {
        $this->isModal = true;
    }

    //Reset Field
    public function resetFields()
    {
        $this->description = '';
        $this->amount = '';
        $this->type = '';
        
    }

    //Save/Update data
    public function store()
    {
        //Validation
        $this->validate([
            'description' => 'required|string',
            'amount' => 'required|string',
            'type' => 'required|string',
        ]);

        Income::updateOrCreate(['id' => $this->description], [
            'description' => $this->description,
            'amount' => $this->amount,
            'typer' => $this->type,
            
        ]);

        //NOTIFICATION
        session()->flash('message', $this->description ? $this->description . ' Editted': $this->description . ' Added');
        $this->closeModal(); //CLOSE MODAL
        $this->resetFields(); //CLEAR FIELD
    }

    // GET DATA BY ID
    public function edit($id)
    {
        $incomes = Income::find($id); //GET DATA
        //ASIGN BASED ON DATA
        $this->description = $incomes->description;
        $this->amount = $incomes->amount;
        $this->type = $incomes->type;

        $this->openModal(); //OPEN MODAL
    }

    //DELETE FUNCTION
    public function delete($id)
    {
        $incomes = Income::find($id); //BY ID
        $incomes->delete(); //DELETE DATA
        session()->flash('message', $incomes->description . ' Deleted'); //NOTIFICATION
    }
}