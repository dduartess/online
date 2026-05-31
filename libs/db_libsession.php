<?php
/*
 * Session persistence for legacy online module.
 */

function db_savesession($_conn, $_session) {

  $sql = "SELECT fc_startsession();";
  $result = pg_query($_conn, $sql) or die("Nao foi possivel criar sessao no banco de dados (Sql: $sql)!");

  if (pg_num_rows($result) == 0) {
    return false;
  }

  // Use only fc_putsession to avoid invalid dynamic table names like "ecidade.".
  $sql = "";

  foreach ($_session as $key => $val) {

    $keyUpper = strtoupper($key);

    if ($keyUpper == "DB_DATAUSU") {
      $val = date("Y-m-d", (int)$val);
    }

    if (substr($keyUpper, 0, 2) == "DB") {
      $val = pg_escape_string((string)$val);
      $sql .= "SELECT fc_putsession('{$keyUpper}', '{$val}'); ";
    }
  }

  if ($sql !== "") {
    pg_query($_conn, $sql) or die("Nao foi possivel criar sessao no banco de dados (Sql: $sql)!");
  }

  return true;
}
?>
