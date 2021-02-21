<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Expense;

class Expenses extends Component
{
    public $expenses, $description, $amount, $category;
    public $isModal = 0;

  	//LOAD VIEW
    public function render()
    {
        $this->expenses = Expense::orderBy('created_at', 'DESC')->get(); //CREATE QUERY FOR GETDATA
        return view('livewire.expenses'); //LOAD VIEW Expense.BLADE.PHP INSIDE /RESOURSCES/VIEWS/LIVEWIRE
    }

    //Start as add Expense button clicked
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
            'category' => 'required|string',
        ]);

        Expense::updateOrCreate(['id' => $this->description], [
            'description' => $this->description,
            'amount' => $this->amount,
            'category' => $this->category,
            
        ]);

        //NOTIFICATION
        session()->flash('message', $this->description ? $this->description . ' Editted': $this->description . ' Added');
        $this->closeModal(); //CLOSE MODAL
        $this->resetFields(); //CLEAR FIELD
    }

    // GET DATA BY ID
    public function edit($id)
    {
        $expenses = Expense::find($id); //GET DATA
        //ASIGN BASED ON DATA
        $this->description = $expenses->description;
        $this->amount = $expenses->amount;
        $this->category = $expenses->category;

        $this->openModal(); //OPEN MODAL
    }

    //DELETE FUNCTION
    public function delete($id)
    {
        $expenses = Expense::find($id); //BY ID
        $expenses->delete(); //DELETE DATA
        session()->flash('message', $expenses->description . ' Deleted'); //NOTIFICATION
    }
}