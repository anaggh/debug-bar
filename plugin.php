<?php

class DebugBarPlugin extends Plugin
{
    public function afterSiteLoad()
    {
        register_shutdown_function(['DebugBarPlugin', 'renderDebugBar']);
    }

    public function afterAdminLoad()
    {
        register_shutdown_function(['DebugBarPlugin', 'renderDebugBar']);
    }

    protected static function benchmark()
    {
        $timeTaken = 'Time : ' . round((microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]), 3) . 's';
        $memoryUsed = 'Mem Use: ' . round((memory_get_usage()/1048576), 2)  . ' MB';
        return $timeTaken . ' | ' . $memoryUsed;
    }

    public function renderDebugBar()
    {
        $includedFiles = get_included_files();
        $includedFilesCount = count($includedFiles);
        $includedFilesHtml = '<table border=1>';
        foreach ($includedFiles as $file) {
            $includedFilesHtml .= "<tr style=\"text-align: left;\"><td>" . Text::replace(PATH_ROOT, '', $file) . '</td></tr>';
        }
        $includedFilesHtml .= '</table>';

        echo "
<script src='https://cdn.jsdelivr.net/npm/sweetalert2@8'></script>

<script>
function showDebugBarIncludedFiles()
{
    Swal.fire({
      title: 'Included Files ({$includedFilesCount})',
      html: '{$includedFilesHtml}',
      confirmButtonText: 'Close',
      animation: false
    })
}
</script>

<style>
.sticky-debug-bar {
  position: fixed;
  left: 0;
  bottom: 0;
  width: 100%;
  background-color: rgba(0,0,0, 0.7);
  color: white;
  text-align: center;
  padding: 10px;
}
</style>

<div class='sticky-debug-bar'>
  <a onClick='showDebugBarIncludedFiles()' style='text-decoration: underline; cursor: pointer;'>Files Included ({$includedFilesCount})</a> ".self::benchmark()." | <a href='".HTML_PATH_ADMIN_ROOT."developers' target='_blank'>Developers</a>
</div>
";
    }
}
