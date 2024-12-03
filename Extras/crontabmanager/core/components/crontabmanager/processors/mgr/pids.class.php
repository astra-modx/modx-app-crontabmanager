<?php

/**
 * Вернет список задание
 */
class CronTabManagerPidsProcessor extends modProcessor
{
    /**
     * @return array|string
     */
    public function process()
    {
        $out = '';
        if ($arrays = \Webnitros\CronTabManager\Pid::pids()) {
            $path = MODX_CORE_PATH.'scheduler/ControllersLinks/'; // путь к папке с контроллерами
            $list = [];
            if (file_exists($path)) {
                foreach ($arrays as $array) {
                    $process = str_ireplace($path, '', $array['process']);
                    $start = $array['start'];
                    $list[] = "<tr><td>$start</td><td>$process</td></tr>";
                }
            }
            $str = implode('', $list);
            $out = '<table>
  <thead>
    <tr>
      <th width="100">Run start</th>
      <th>Task</th>
    </tr>
  </thead>
  <tbody>'.$str.'
  </tbody>
</table>';
        }

        if (empty($out)) {
            $out = 'No processes';
        }
        $today = date('H:i', time());
        exit('# pids - time '.$today.'<pre style="background-color: #eee; overflow-x: scroll; padding: 5px 15px" contenteditable="true">'.' '.PHP_EOL.$out.PHP_EOL.PHP_EOL.'</pre>');
    }
}

return 'CronTabManagerPidsProcessor';
