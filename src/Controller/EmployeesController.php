<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Employees Controller
 *
 * @property \App\Model\Table\EmployeesTable $Employees
 * @method \App\Model\Entity\Employee[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EmployeesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index($finder = 'all')
    {
        $this->paginate = [
            'contain'   => ['Teams', 'Roles', 'Reports', 'Statuses'],
            'limit'     => 100,
            'order'     => ['Employees.first_name' => 'ASC']
        ];
        $employees = $this->paginate($this->Employees->find($finder));

        $this->set(compact('employees'));
    }

    /**
     * View method
     *
     * @param string|null $id Employee id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $employee = $this->Employees->get($id, [
            'contain' => ['Teams', 'Roles', 'Reports', 'Statuses', 'StatusChanges'],
        ]);

        $this->set(compact('employee'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $employee = $this->Employees->newEmptyEntity();
        if ($this->request->is('post')) {
            $employee = $this->Employees->patchEntity($employee, $this->request->getData());
            if ($this->Employees->save($employee)) {
                $this->Flash->success(__('The employee has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The employee could not be saved. Please, try again.'));
        }
        $teams = $this->Employees->Teams->find('list')->order(['name' => 'ASC']);
        $roles = $this->Employees->Roles->find('list', ['limit' => 200, 'order' => ['Roles.name' => 'ASC']]);
        $reports = $this->Employees->Reports->find('list', ['limit' => 200])->order(['first_name' => 'ASC']);
        $statuses = $this->Employees->Statuses->find('list', ['limit' => 200]);
        $this->set(compact('employee', 'teams', 'roles', 'reports', 'statuses'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Employee id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $employee = $this->Employees->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $employee = $this->Employees->patchEntity($employee, $this->request->getData());
            if ($this->Employees->save($employee)) {
                $this->Flash->success(__('The employee has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The employee could not be saved. Please, try again.'));
        }
        $teams = $this->Employees->Teams->find('list')->order(['name' => 'ASC']);
        $roles = $this->Employees->Roles->find('list', ['limit' => 200, 'order' => ['Roles.name' => 'ASC']]);
        $reports = $this->Employees->Reports->find('list', ['limit' => 200])->order(['first_name' => 'ASC']);
        $statuses = $this->Employees->Statuses->find('list', ['limit' => 200]);
        $this->set(compact('employee', 'teams', 'roles', 'reports', 'statuses'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Employee id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $employee = $this->Employees->get($id);
        if ($this->Employees->delete($employee)) {
            $this->Flash->success(__('The employee has been deleted.'));
        } else {
            $this->Flash->error(__('The employee could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function getList()
    {
        $employees = $this->Employees->find('employed')->contain(['Teams']);
        echo "<table>";
        foreach ($employees as $e) 
        {
            echo "<tr><td>" . ($e->has('team') ? $e->team->name : ' ') . "</td>" .

              '<td>' . $e->last_name . ' ' . $e->first_name . '</td></tr>';
        }
        echo "</table>";
        die;

    }
}
