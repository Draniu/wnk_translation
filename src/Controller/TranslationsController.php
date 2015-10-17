<?php
namespace WnkTranslation\Controller;

use WnkTranslation\Controller\AppController;
use Cake\Core\Configure;

/**
 * Translations Controller
 *
 * @property \WnkTranslation\Model\Table\TranslationsTable $Translations
 */
class TranslationsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('WnkTranslation.Utltrans');
    }


    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {

        $where = array();
        if (!empty($this->request['data']['locale']))
            $where['locale'] = $this->request['data']['locale'];

        if (!empty($this->request['data']['msgstr']))
            $where['msgstr like'] = '%' . $this->request['data']['msgstr'] . '%';

        if (!empty($this->request['data']['status']))
            $where['status'] = $this->request['data']['status'];

        $query = $this->Translations->find('all')->where($where);

        $this->set('translations', $this->paginate($query));
        $this->set('_serialize', ['translations']);
        $this->set('WnkTranslation', Configure::read('WnkTranslation'));
    }

    /**
     * View method
     *
     * @param string|null $id Translation id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $translation = $this->Translations->get($id, [
            'contain' => []
        ]);
        $this->set('translation', $translation);
        $this->set('_serialize', ['translation']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $translation = $this->Translations->newEntity();
        if ($this->request->is('post')) {
            $translation = $this->Translations->patchEntity($translation, $this->request->data);
            if ($this->Translations->save($translation)) {
                $this->Flash->success(__('The translation has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The translation could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('translation'));
        $this->set('_serialize', ['translation']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Translation id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $translation = $this->Translations->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $translation = $this->Translations->patchEntity($translation, $this->request->data);
            if ($this->Translations->save($translation)) {
                $this->Flash->success(__('The translation has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The translation could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('translation'));
        $this->set('_serialize', ['translation']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Translation id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $translation = $this->Translations->get($id);
        if ($this->Translations->delete($translation)) {
            $this->Flash->success(__('The translation has been deleted.'));
        } else {
            $this->Flash->error(__('The translation could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }


    public function import() 
    {
        $this->autoRender = false;
        $rc = $this->Utltrans->import();
        $this->Flash->success(__('Import ended.') . ' ' . $rc);
        return $this->redirect(['action' => 'index']);
    }

    public function prepare() 
    {
        $this->autoRender = false;
        $rc = $this->Utltrans->prepare();
        $this->Flash->success(__('Translate ended.'));
        return $this->redirect(['action' => 'index']);
    }

    public function export() 
    {
        $this->autoRender = false;
        $rc = $this->Utltrans->export();
        $this->Flash->success($rc);
        return $this->redirect(['action' => 'index']);
    }

    public function about() 
    {
    }

}