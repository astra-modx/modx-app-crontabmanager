<?php


class CronTabManagerRuleGetNodesProcessor extends modProcessor
{
    /**
     * @var mixed|string
     */
    public $checkeds = [];
    protected $categories = array();


    /**
     * @return bool
     */
    public function initialize()
    {

        // отправка email уведомления
        /* @var CronTabManager $CronTabManager */
        $CronTabManager = $this->modx->getService('crontabmanager', 'CronTabManager', MODX_CORE_PATH . 'components/crontabmanager/model/');


        $categories = $this->getProperty('categories');

        $this->checkeds = $this->modx->fromJSON($categories);

        return parent::initialize();
    }


    public function add($id, $name, $hasChildren = true, $hide_children_in_tree = false, $type = 'category')
    {
        $array = array(
            "classKey" => "modDocument",
            "cls" => "icon-mscategory haschildre",
            "ctx" => "web",
            "disabled" => false,
            "hasChildren" => $hasChildren,
            "hide_children_in_tree" => $hide_children_in_tree,
            "iconCls" => $type !== 'category' ? "icon icon-barcode parent-resource" : "tree-resource tree-folder parent-resource",
            #"iconCls" => "tree-resource tree-folder parent-resource",
            "id" => "web_" . $id,
            "pk" => $id,
            "qtip" => "<b></b>",
            "text" => $name,
            "type" => "modResource"
        );

        if ($type === 'task') {
            $array['checked'] = array_key_exists($id, $this->checkeds) ? true : false;
        }
        $this->categories[] = $array;
    }

    public function process()
    {


        $id = $this->getProperty('id');
        if ($id === 'root') {
            $q = $this->modx->newQuery('CronTabManagerCategory');
            $q->select('id, name');
            if ($q->prepare() && $q->stmt->execute()) {
                while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                    $this->add($row['id'], $row['name'], true);
                }
            }
        } else {

            $id = str_ireplace('web_', '', $id);

            $q = $this->modx->newQuery('CronTabManagerTask');
            $q->select('path_task, id');
            $q->where(array(
                'parent' => $id
            ));
            if ($q->prepare() && $q->stmt->execute()) {
                while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                    $this->add($row['id'], $row['path_task'], false, true, 'task');
                }
            }
        }

        session_write_close();
        exit(json_encode($this->categories));
        // TODO: Implement process() method.
    }

}

return 'CronTabManagerRuleGetNodesProcessor';
