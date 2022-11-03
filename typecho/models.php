<?php

namespace Langnang\Module\Typecho;



class TypechoModel
{
  protected $_template;
  function set__template()
  {
    global $_TWIG;
    $this->_template = $_TWIG->load('typecho/sql.twig');
  }
  function get__template($block_name, $args = [])
  {
    $vars = [];
    foreach ($this as $key => $value) {
      $vars[$key] = $value;
    }
    var_dump($block_name);
    return $this->_template->renderBlock($block_name, array_merge($vars, $args));
  }
  /**
   * @var string
   */
  protected $_prefix = "typecho_";
  // 表名
  protected $_table_name;
  function set_prefix($value)
  {
    if (!is_null($value)) $this->_prefix = $value;
  }
  function get_prefix()
  {
    return $this->_prefix;
  }
  /**
   * @var string
   */
  protected $_primary_key;
  function set_primary_key($value)
  {
    if (!is_null($value)) $this->_primary_key = $value;
  }
  function get_primary_key()
  {
    return $this->_primary_key;
  }
  protected $_parent_key;
  function set_parent_key($value)
  {
    if (!is_null($value)) $this->_parent_key = $value;
  }
  function get_parent_key()
  {
    return $this->_parent_key;
  }
  protected $_root = 0;
  function set__root($value)
  {
    if (!is_null($value)) $this->_root = (int)$value;
  }
  function get__root()
  {
    return $this->_root;
  }
  function set_mid($value)
  {
    if (!is_null($value)) $this->mid = (int)$value;
  }
  function set_mids($value)
  {
    if (!is_null($value)) {
      if (is_array($value)) {
        $value = $value;
      } else if (is_string($value)) {
        $value = explode(",", $value);
      } else {
        $value = (array)$value;
      }
      $this->mids = array_map(function ($item) {
        return (int)$item;
      }, $value);
    }
  }
  function get_mids()
  {
    return $this->mids;
  }
  function set_cid($value)
  {
    if (!is_null($value)) $this->cid = (int)$value;
  }
  function set_cids($value)
  {
    if (!is_null($value)) {
      if (is_array($value)) {
        $value = $value;
      } else if (is_string($value)) {
        $value = explode(",", $value);
      } else {
        $value = (array)$value;
      }
      $this->cids = array_map(function ($item) {
        return (int)$item;
      }, $value);
    }
  }
  function get_cids()
  {
    return $this->cids;
  }

  // 新增SQL语句生成所需的列名
  protected $_insert_columns = [];
  // 生成新增SQL语句
  function get_sql_insert()
  {
    return "
INSERT INTO `{$this->_table_name}` 
  ("
      . substr(array_reduce($this->_insert_columns, function ($total, $column) {
        if (!is_null($this->{$column})) {
          return $total . ", `{$column}`";
        }
        return $total;
      }, ""), 2)
      . ") 
VALUES 
  (" .
      substr(array_reduce($this->_insert_columns, function ($total, $column) {
        if (!is_null($this->{$column})) {
          if (is_int($this->{$column})) {
            return $total . ", {$this->{$column}}";
          } else {
            return $total . ", '{$this->{$column}}'";
          }
        }
        return $total;
      }, ""), 2)
      .
      ");";
  }
  // 多项删除SQL语句生成所需的主键
  protected $_delete_primary_key_values = [];
  function get_delete_primary_key_values()
  {
    return $this->_delete_primary_key_values;
  }
  // 生成删除SQL语句
  function get_sql_delete()
  {

    return "DELETE FROM `{$this->_table_name}` WHERE `{$this->get_primary_key()}` IN ("
      . substr(array_reduce((array)$this->_delete_primary_key_values, function ($total, $item) {
        if (is_int($item)) {
          return $total . ", {$item}";
        } else {
          return $total . ", '{$item}'";
        }
      }, ""), 2)
      . ")";
  }
  // 更新SQL语句生成所需的列名
  protected $_update_columns = [];
  // 生成修改SQL语句
  function get_sql_update()
  {
    return "UPDATE `{$this->_table_name}`
    SET "
      . substr(array_reduce($this->_update_columns, function ($total, $column) {
        if (!is_null($this->{$column})) {
          if (is_int($this->{$column})) {
            return $total . ", `{$column}` = {$this->{$column}}";
          } else {
            return $total . ", `{$column}` = '{$this->{$column}}'";
          }
        }
        return $total;
      }, ""), 2)
      . " WHERE `{$this->get_primary_key()}` = {$this->{$this->get_primary_key()}}
    ";
  }
  // 生成查询SQL语句
  function get_sql_select($page = 1, $limit = 20)
  {
    return "SELECT * FROM `{$this->_table_name}`" . $this->get_sql_condition();
  }
  // 生成计数SQL语句
  function get_sql_count($columns = [])
  {
    $columns = (array)$columns;
    return "SELECT COUNT(*) AS `count_total`"
      . array_reduce($columns, function ($t, $v) {
        return $t . ", `{$v}`";
      }, "")
      . " FROM `{$this->_table_name}`"
      . $this->get_sql_condition()
      // GROUP BY
      . (empty($columns) ? "" : (" GROUP BY "
        . substr(array_reduce($columns, function ($t, $v) {
          return $t . ", `{$v}`";
        }, ""), 2)
      ));
  }
  // 生成
  function get_sql_distinct($column)
  {
    return "SELECT DISTINCT `{$column}` FROM `{$this->_table_name}`" . $this->get_sql_condition();
  }
  // 生成查询条件SQL语句
  function get_sql_condition()
  {
    return " WHERE 1 = 1";
  }
  protected $_concat_columns = [];

  function get_sql_concat()
  {
    return " CONCAT( '[', GROUP_CONCAT( '{',"
      . array_reduce($this->_concat_columns, function ($total, $item) {
        return $total . "'\"{$item}\":\"', CASE WHEN ISNULL(`{$item}`) THEN '' ELSE `{$item}` END , '\",' ,";
      }, "")
      . "'}' SEPARATOR ', ' ), ']' ) ";
  }
  function __sql_condition()
  {
  }
}

/**
 * @OA\Schema(schema="TypechoMetaModel")
 */
class TypechoMetaModel extends TypechoModel
{
  protected $_primary_key = 'mid';
  protected $_parent_key = 'mid';
  /**
   * @OA\Property(default=0, format="int(10)", title="标识ID")
   * @var int
   * @primary true
   */
  public $mid;
  /**
   * @OA\Property(default=NULL, format="varchar(200)", title="名称")
   * @var string
   */
  public $name;
  /**
   * @OA\Property(default=NULL, format="varchar(32)", title="编码")
   * @var string
   * @type varchar(32)
   */
  public $slug;
  /**
   * @OA\Property(default=NULL, format="varchar(200)", title="类型")
   * @var string
   * @type varchar(200)
   */
  public $type;
  private $type_elements = ["category", "tag", "branch", "menu", "option"];
  /**
   * @OA\Property(default=NULL, format="varchar(200)", title="描述")
   * @var string
   * @type varchar(200)
   */
  public $description;
  /**
   * @OA\Property(default=NULL, format="int(10)", title="计数管理内容数")
   * @var int
   */
  public $count;
  /**
   * @OA\Property(default=NULL, format="int(10)", title="排序权重")
   * @var int
   */
  public $order;
  /**
   * @OA\Property(default=NULL, format="int(10)", title="父本ID")
   * @var int
   * @type int(10)
   */
  public $parent;
  /**
   * @OA\Property(default=NULL, format="", title="批量处理ID")
   */
  protected $mids;
  /**
   * @OA\Property(default=NULL, format="", title="关联内容ID")
   */
  public $cids;
  function __construct($args = array(), $is_mock = false)
  {
    $this->set_mid($args['mid']);
    $this->name = isset($args['name']) ? $args['name'] : null;
    $this->slug = isset($args['slug']) ? $args['slug'] : null;
    $this->type = isset($args['type']) ? $args['type'] : null;
    $this->description = isset($args['description']) ? $args['description'] : null;
    $this->count = isset($args['count']) ? (int)$args['count'] : null;
    $this->order = isset($args['order']) ? (int)$args['order'] : null;
    $this->parent = isset($args['parent']) ? (int)$args['parent'] : null;
    $this->set_mids($args['mids']);
    $this->set_cids($args['cids']);

    if ($is_mock) {
      $this->__mock();
    }

    $this->set__template();
    $this->_table_name = $this->_prefix . "metas";
    $this->_insert_columns = ["name", "slug", "type", "description", "count", "order", "parent"];
    $this->_delete_primary_key_values = array_merge((array)$this->mid, (array)$this->mids);
    $this->_update_columns = array_merge([$this->get_primary_key()], $this->_insert_columns);
    $this->_concat_columns = $this->_update_columns;
  }
  function __mock()
  {
    if (!strpos($_SERVER['HTTP_REFERER'], "swagger")) return;
    global $_FAKER;
  }
  function get_sql_select($page = 1, $limit = 20)
  {
    $offset = ($page - 1) * $limit;
    $relationships = new TypechoRelationshipModel();
    $relationships->set_prefix($this->get_prefix());
    return "SELECT 
  `tb_metas`.*, `tb_relationships`.`cids` 
FROM 
  ( SELECT * FROM `{$this->_prefix}metas` {$this->get_sql_condition()} LIMIT {$limit} OFFSET {$offset} ) AS `tb_metas`
LEFT JOIN 
  ({$relationships->get_sql_group("mid")}) AS `tb_relationships` 
USING(`mid`)
  ";
  }
  function get_sql_condition()
  {
    $result = " WHERE 1 = 1";
    // 精准查询
    if (!empty($this->mid)) return $result . " AND `mid` = {$this->mid}";
    // 条件查询
    if (!empty($this->name)) $result .= " AND `name` LIKE '%{$this->name}%'";
    if (!empty($this->slug)) $result .= " AND `slug` = '{$this->slug}'";
    if (!empty($this->type)) $result .= " AND `type` = '{$this->type}'";
    if (!empty($this->description)) $result .= " AND `description` LIKE '%{$this->description}%'";
    if (!empty($this->count)) $result .= " AND `count` = {$this->count}";
    if (!empty($this->order)) $result .= " AND `order` = {$this->order}";
    if (!empty($this->parent) || $this->parent === 0) $result .= " AND `parent` = {$this->parent}";
    if (!empty($this->mids)) $result .= " AND `mid` IN (" . implode(",", $this->mids) . ")";
    return $result;
  }
}
/**
 * @OA\Schema(schema="TypechoContentModel")
 */
class TypechoContentModel extends TypechoModel
{
  protected $_primary_key = 'cid';
  protected $_parent_key = 'cid';
  /**
   * @OA\Property(default=0, format="int(10)", title="内容ID")
   * @var int
   * @primary true
   */
  public $cid;
  /**
   * @OA\Property(default=NULL, format="varchar(200)", title="标题")
   * @var string
   */
  public $title;
  /**
   * @OA\Property(default=NULL, format="varchar(200)", title="编码")
   * @var string
   */
  public $slug;
  /**
   * @OA\Property(default=NULL, format="int(10)", title="创建时间")
   * @var int
   */
  public $created;
  /**
   * @OA\Property(default=NULL, format="int(10)", title="修改时间")
   * @var int
   */
  public $modified;
  /**
   * @OA\Property(default=NULL, format="longtext", title="内容")
   * @var string
   */
  public $text;
  /**
   * @OA\Property(default=0, format="int(10)", title="排序权重")
   * @var int
   */
  public $order;
  /**
   * @OA\Property(default=0, format="int(10)", title="用户ID")
   * @var int
   */
  public $authorId;
  /**
   * @OA\Property(default=NULL, format="varchar(32)", title="模板")
   * @var string
   */
  public $template;
  /**
   * @OA\Property(default="post", format="varchar(16)", title="类型")
   * @var string
   */
  public $type;
  private $type_elements = [
    'post' => '文章',
    'template' => '模板',
  ];
  /**
   * @OA\Property(default="publish", format="varchar(16)", title="状态")
   * @var string
   */
  public $status;
  private $status_elements = [
    'publish' => '公开',
    'draft' => '草稿'
  ];
  /**
   * @OA\Property(default=NULL, format="varchar(32)", title="访问密码")
   * @var string
   */
  public $password;
  /**
   * @OA\Property(default=0, format="int(10)", title="回复数")
   * @var int
   */
  public $commentsNum;
  /**
   * @OA\Property(default="0", format="char(1)", title="允许回复")
   * @var string
   */
  public $allowComment;
  /**
   * @OA\Property(default="0", format="char(1)", title="允许回显")
   * @var string
   */
  public $allowPing;
  /**
   * @OA\Property(default="0", format="char(1)", title="允许反馈")
   * @var string
   */
  public $allowFeed;
  /**
   * @OA\Property(default=0, format="int(10)", title="父本ID")
   * @var int
   */
  public $parent;
  /**
   * @OA\Property()
   * @var int
   * @type int(10)
   */
  public $support;
  /**
   * @OA\Property()
   * @var int
   * @type int(10)
   */
  public $views;
  /**
   * @OA\Property(default=NULL, format="", title="批量处理ID")
   */
  protected $cids;
  /**
   * @OA\Property(default=NULL, format="", title="关联标识ID")
   */
  public $mids;
  /**
   * @OA\Property(default=NULL, format="", title="附加字段")
   */
  public $fields;
  function __construct($args = array(), $is_mock = false)
  {
    $this->cid = isset($args['cid']) ? (int)$args['cid'] : null;
    $this->title = isset($args['title']) ? $args['title'] : null;
    $this->slug = isset($args['slug']) ? $args['slug'] : null;
    $this->created = isset($args['created']) ? (int)$args['created'] : null;
    $this->modified = isset($args['modified']) ? (int)$args['modified'] : null;
    $this->text = isset($args['text']) ? $args['text'] : null;
    $this->order = isset($args['order']) ? (int)$args['order'] : null;
    $this->authorId = isset($args['authorId']) ? (int)$args['authorId'] : null;
    $this->template = isset($args['template']) ? $args['template'] : null;
    $this->type = isset($args['type']) ? $args['type'] : null;
    $this->status = isset($args['status']) ? $args['status'] : null;
    $this->password = isset($args['password']) ? $args['password'] : null;
    $this->commentsNum = isset($args['commentsNum']) ? (int)$args['commentsNum'] : null;
    $this->allowComment = isset($args['allowComment']) ? $args['allowComment'] : null;
    $this->allowPing = isset($args['allowPing']) ? $args['allowPing'] : null;
    $this->allowFeed = isset($args['allowFeed']) ? $args['allowFeed'] : null;
    $this->parent = isset($args['parent']) ? (int)$args['parent'] : null;
    $this->support = isset($args['support']) ? (int)$args['support'] : null;
    $this->views = isset($args['views']) ? (int)$args['views'] : null;
    $this->set_mids(isset($args['mids']) ? $args['mids'] : null);
    $this->set_cids(isset($args['cids']) ? $args['cids'] : null);

    if ($is_mock) {
      $this->__mock();
    }
    $this->set__template();
    $this->_table_name = $this->_prefix . "contents";
    $this->_insert_columns = ["title", "slug", "created", "modified", "text", "order", "authorId", "template", "type", "status", "password", "commentsNum", "allowComment", "allowPing", "allowFeed", "parent", "support", "views"];
    $this->_delete_primary_key_values = array_merge((array)$this->cid, (array)$this->cids);
    $this->_update_columns = array_merge([$this->get_primary_key()], $this->_insert_columns);
    $this->_concat_columns = $this->_update_columns;
  }
  function __mock()
  {
    if (!strpos($_SERVER['HTTP_REFERER'], "swagger")) return;
    global $_FAKER;
    $this->title = $_FAKER->sentence();
    $this->slug = $_FAKER->word();
    $this->created = $_FAKER->unixTime();
    $this->modified = $_FAKER->unixTime();
    $this->text = $_FAKER->text();
    $this->status = $_FAKER->randomKey($this->status_elements);
  }
  function get_sql_select($page = 1, $limit = 20, $index = 0)
  {

    $offset = ($page - 1) * $limit;
    $relationships = new TypechoRelationshipModel();
    // $metas = new TypechoMetaModel();
    $relationships->set_prefix($this->get_prefix());
    return "SELECT 
      `tb_contents_{$index}`.*, `tb_relationships_{$index}`.`mids` 
    FROM 
      ( SELECT * FROM `{$this->_prefix}contents` {$this->get_sql_condition()} ORDER BY `modified` DESC LIMIT {$limit} OFFSET {$offset} ) AS `tb_contents_{$index}`
    LEFT JOIN 
      ({$relationships->get_sql_group("cid",$index + 1)}) AS `tb_relationships_{$index}` 
    USING(`cid`) 
          ";
    //     return "SELECT 
    //   `tb_contents_{$index}`.*, `tb_relationships_{$index}`.`mids` 
    // FROM 
    //   ( SELECT * FROM `{$this->_prefix}contents` {$this->get_sql_condition()} ORDER BY `modified` DESC LIMIT {$limit} OFFSET {$offset} ) AS `tb_contents_{$index}`
    // LEFT JOIN 
    //   ({$relationships->get_sql_group("cid",$index + 1)}) AS `tb_relationships_{$index}` 
    // USING(`cid`) 
    // LEFT JOIN ( 
    //   SELECT * FROM (
    //     {$relationships->get_sql_select(1, PHP_INT_MAX,$index + 1)} 
    //     LEFT JOIN ({$metas->get_sql_select(1, PHP_INT_MAX,$index + 1)}) 
    //       USING (`mid`)
    //   )
    // ) 
    //   USING (`cid`)
    //       ";
  }
  function get_sql_condition()
  {
    $result = " WHERE 1 = 1";
    // 精准查询
    if (!is_null($this->cid)) return $result . " AND `cid` = {$this->cid}";
    // 条件查询
    if (!is_null($this->title)) $result .= " AND `title` LIKE '%{$this->title}%'";
    if (!is_null($this->slug)) $result .= " AND `slug` = '{$this->slug}'";
    if (!is_null($this->created)) $result .= " AND `created` >= {$this->created}";
    if (!is_null($this->modified)) $result .= " AND `modified` >= {$this->modified}";
    if (!is_null($this->authorId)) $result .= " AND `authorId` = {$this->authorId}";
    if (!is_null($this->type)) $result .= " AND `type` = '{$this->type}'";
    if (!is_null($this->status)) $result .= " AND `status` = '{$this->status}'";
    if (!is_null($this->parent)) $result .= " AND `parent` = {$this->parent}";
    if (!is_null($this->cids)) $result .= " AND `cid` IN (" . implode(",", $this->cids) . ")";
    return $result;
  }
}
/**
 * @OA\Schema(schema="TypechoFieldModel")
 */
class TypechoFieldModel extends TypechoModel
{
  /**
   * @OA\Property()
   * @var int
   * @type int(10)
   * @primary true
   */
  public $cid;
  /**
   * @OA\Property()
   * @var string
   * @type varchar(200)
   */
  public $name;
  /**
   * @OA\Property()
   * @var string
   * @type varchar(8)
   */
  public $type;
  /**
   * @OA\Property()
   * @var string
   * @type text
   */
  public $str_value;
  /**
   * @OA\Property()
   * @var int
   * @type int(10)
   */
  public $int_value;
  /**
   * @OA\Property()
   * @var float
   * @type float
   */
  public $float_value;
  function __construct($args = array())
  {
  }
}
/**
 * @OA\Schema(schema="TypechoRelationshipModel")
 */
class TypechoRelationshipModel extends TypechoModel
{
  /**
   * @OA\Property()
   * @var int
   * @type int(10)
   */
  public $mid;
  /**
   * @OA\Property()
   * @var int
   * @type int(10)
   */
  public $cid;
  public $mids;
  public $cids;
  function __construct($args = array())
  {
    $this->mid = isset($args['mid']) ? (int)$args['mid'] : null;
    $this->cid = isset($args['cid']) ? (int)$args['cid'] : null;
    $this->mids = isset($args['mids']) ? (array)$args['mids'] : null;
    $this->cids = isset($args['cids']) ? (array)$args['cids'] : null;

    $this->_table_name = $this->_prefix . "relationships";
  }
  function get_sql_group($group_by_column)
  {
    if ($group_by_column == 'cid') {
      return "SELECT cid, GROUP_CONCAT( mid SEPARATOR ',' ) AS mids, CONCAT( ',', GROUP_CONCAT( mid SEPARATOR ',' ), ',' )  AS concat_mids  FROM `{$this->_prefix}relationships` GROUP BY cid";
    } else if ($group_by_column == 'mid') {
      return "SELECT mid, GROUP_CONCAT( cid SEPARATOR ',' ) AS cids, CONCAT( ',', GROUP_CONCAT( cid SEPARATOR ',' ), ',' )  AS concat_cids FROM `{$this->_prefix}relationships` GROUP BY mid";
    }
    return "";
  }
}
