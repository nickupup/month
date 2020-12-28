<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Discount extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //接收数据
        $keyword = input('keyword');
        //设置条件数组
        $where = [];
        //判断是否传输数据
        if (!empty($keyword)) {
            $where['name|type'] = ['like',"%$keyword%"];
        }
        $data = model('Discount')->where($where)->order('sort', 'asc')->paginate(5);
        return view('list', ['data' => $data]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param \think\Request $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //接收数据
        $param = $request->param();
        //验证数据
        $validate = $this->validate($param, [
            'name' => 'require|unique:discount',
            'dis_price' => 'require|number|gt:0',
            'small_price' => 'require|number|gt:0',
            'type' => 'require',
            'indate' => 'require',
        ]);
        if ($validate !== true) {
            return json(['code' => 500, 'msg' => $validate, 'data' => []]);
        }
        //入库保存
        $res = model('Discount')::create($param, true);
        return json(['code' => 200, 'msg' => '添加成功', 'data' => $res]);
    }

    /**
     * 显示指定的资源
     *
     * @param int $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param int $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param \think\Request $request
     * @param int $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param int $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //验证数据
        if (!is_numeric($id)||$id<=0){
            $this->error('参数格式错误');
        }
        //查询本条数据
        $data=model('Discount')->find($id);
        //判断优惠券是否有效
        if ($data['state']==1){
            $this->error('有效的优惠券，无法删除');
        }
        //删除
        $data->delete();
        $this->redirect('admin/Discount/index');
    }
}
