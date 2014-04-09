<?php
function init()
{
  global $_config;
  global $file_to_write; 
  if ($_config['store_type'] == 'mysql')
  {
     mysql_connect($_config['mysql_host'],$_config['mysql_login'],$_config['mysql_password']);
     mysql_selectdb($_config['mysql_db']);
     mysql_query($_config['mysql_init_sql']);
  }
  if ($_config['store_type'] == 'file')
  {
    $file_to_write = fopen($file_begin.'.nidx','w');
  }
  if ($_config['store_type'] == 'stdout')
  {
    print 'BEGIN_TIME:'.date('r')."\n";
  }
}

function final_work()
{
  global $_config;
  global $file_to_write;
  if ($_config['store_type'] == 'mysql')
  {
     mysql_close();
  }
  if ($_config['store_type'] == 'file')
  {
    fclose($file_to_write);
  }
  if ($_config['store_type'] == 'stdout')
  {
    print 'END_TIME:'.date('r')."\n";
  }
}
function timestamp_show()
{
    print ''.date('r')."\n";
}

function nodes_get($nodes_ids)
{
  global $_config;
  if (!is_array($nodes_ids))
  {
    $nodes_ids = array($nodes_ids);
  } 
  if ($_config['store_type'] == 'mysql')
  {
     $result = array();
     $sql = 'SELECT * FROM nodes WHERE node_id IN ( '.join(',',$nodes_ids).' )';
     $qh = mysql_query($sql);
     while ($row = mysql_fetch_array($qh, MYSQL_ASSOC)) 
     {
        $result[$row['node_id']] = $row;
     }
     return $result;
  }
}

