<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Class
 */
class POP_Model extends CI_Model
{
    const T_FASHION_FM_PRIVILEGE_CLOUD = '`pop136`.`fm_privilege_cloud`'; // 云图权限表
    // const T_FASHION_FM_PRIVILEGE_CLOUD = '`fashion`.`fm_privilege_cloud`'; // 不存在的表

    public function __construct()
    {
        parent::__construct();
        log_message('info', get_class($this) . 'Model Class Initialized');
        // $this->load->database();
    }

    /**
     * 根据SQL查询， 参数通过$param绑定
     * @param string $sql 查询语句，如SELECT * FROM some_table WHERE id = ? AND status = ? AND author = ?
     * @param array $param array(3, 'live', 'Rick')
     * @return array 未找到记录返回空数组，找到记录返回二维数组
     */
    public function query($sql, $param = [])
    {
        return $this->db->query($sql, $param)->result_array();
    }

    /**
     * 更新操作
     * @param $tableName
     * @param $data
     * @param $conditions
     * @return mixed
     */
    public function executeUpdate($tableName, $data, $conditions)
    {
        return $this->db->set($data)->where($conditions)->update($tableName);
    }

    /**
     * 插入操作
     * @param $tableName
     * @param $data
     * @return mixed 返回记录id
     */
    public function executeSave($tableName, $data)
    {
        $this->db->insert($tableName, $data);
        return $this->db->insert_id();
    }

    public function __destruct()
    {
        $this->db->close();
    }
}
