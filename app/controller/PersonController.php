<?php
declare (strict_types = 1);

namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\PersonModel;
use think\Response;
// http://localhost:8010/tp6/public/PersonController/export
// http://localhost:8010/tp6/public/PersonController

/**
 * 图片预处理函数
 * @param  string  $sourcePath      图片源路径
 * @param  string  $destinationPath 图片目标路径
 * @param  integer $maxWidth        生成目标图片最大宽度:默认600
 * @param  integer $maxHeight       生成目标图片最大高度:默认800
 * @param  integer $quality         图片质量:1-100
 * @return boolean                  返回true或false
 */
function compressImage($sourcePath, $destinationPath, $maxWidth = 600, $maxHeight = 800, $quality = 90) {
    // echo "<a href=\"$destinationPath\" download>" . pathinfo($destinationPath)['filename'] . "</a>";
    // echo '<br/>';

    // 获取原始图片信息
    list($sourceWidth, $sourceHeight, $type) = getimagesize($sourcePath);
    // 获取原始图片的宽度和高度
    // $sourceWidth = imagesx($sourceImage);
    // $sourceHeight = imagesy($sourceImage);
    // 根据原始图片类型创建图像资源
    switch ($type) {
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_GIF:
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        default:
            return false; // 不支持的图片类型
    }


    // 计算缩放比例
    $ratio = min($maxWidth / $sourceWidth, $maxHeight / $sourceHeight);

    // 计算新的宽度和高度
    $newWidth = intval($sourceWidth * $ratio);
    $newHeight = intval($sourceHeight * $ratio);

    // echo $newWidth;
    // die();
    // 创建一个新的图片资源，指定新的分辨率
    $newImage = imagecreatetruecolor($newWidth, $newHeight);

    // 将原始图片复制到新的图片资源中，并按照新的分辨率进行缩放
    imagecopyresampled(
        $newImage, $sourceImage,
        0, 0, 0, 0,
        $newWidth, $newHeight,
        $sourceWidth, $sourceHeight
    );

    // 输出到前端
    // header('Content-Type: image/jpeg');
    // imagejpeg($newImage);

    // 保存到本地
    imagejpeg($newImage, $destinationPath, $quality);

    // 释放图片资源
    imagedestroy($sourceImage);
    imagedestroy($newImage);

    return true;
}

class PersonController extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $PersonModel = new PersonModel();
        // $users = $PersonModel->get(1);
        $users = $PersonModel->select();
        $users[0]->each(function($item, $key){
            dump($key);
        });
        // dump($users);
        // 遍历并输出每个用户
        // foreach ($users as $user) {
        //     dump($user->get(1));
        // }
        return;
		// 原始数组
		$numbers = [2, 3, 4, 5, 6];

		// 随机排序数组
		shuffle($numbers);

		// 输出随机排序后的数组
		print_r($numbers);

        $persons = PersonModel::select();
		dump($this);
        $this->assign('persons', $persons);
        return $this->fetch();
    }

    /*
    ;注意服务器PHP.ini对上传文件大小的限制:
    ;允许客户端单个POST请求发送的最大数据
    post_max_size = 8M
    ;是否开启文件上传功能
    file_uploads = On
    ;文件上传的临时存放目录(如果不指定，使用系统默认的临时目录)
    ;upload_tmp_dir =
    ;允许单个请求上传的最大文件大小
    upload_max_filesize = 2M
    ;允许单个POST请求同时上传的最大文件数量
    max_file_uploads = 20
    */
    public function upload()
    {
        // realpath('uploads/') 相当于 G:\www\tp6\public\uploads
        if ($this->request->isPost()) {
            $upname = $this->request->param('fieldname');
            $files = $this->request->file($upname);
            $name = $this->request->param('name');
            $tel = $this->request->param('tel');
            $dept = $this->request->param('dept');
            $md5str = $this->request->param('md5str');

            // $PersonModel = new PersonModel();
            // dump($md5str);

            foreach($files as $key=>$file){
                // 保存照片到服务器，并获取保存路径或URL
                if($file){
                    $OriginalName = $file->getOriginalName();
                    // echo 'uploads/' . $OriginalName;

                    compressImage($file->getPathName(), 'uploads/' . $md5str[$key] . '.jpg');
                    // $info = $file->move('uploads/', $OriginalName);
                    // dump($info);

                    // if ($info) {
                        // $ImgName = $info->getPathName();
                        // echo realpath($ImgName);
                        // $ret = compressImage($ImgName,$ImgName);
                        // echo $ret;
                        // 文件上传成功，返回上传信息
                        // echo '文件上传成功！';
                        // dump($info);
                    // } else {
                        // 文件上传失败，返回错误信息
                        // echo $file->getError();
                    // }
                    // $PersonModel->name = $name;
                    // $PersonModel->tel = $tel;
                    // $PersonModel->dept = $dept;
                    // $PersonModel->picture = $savePath;
                    // $PersonModel->save();
                }
            }
            // $this->success('照片上传成功！');
        }
        // return $this->fetch();
    }


    // 检查MD5值是否已存在的函数
    private function checkMd5Exists($md5) {
        // 这里可以添加你的逻辑来检查MD5值是否已存在
        // 例如，你可以查询数据库或检查文件系统
        // 返回true表示MD5值已存在，false表示不存在
        // 这里仅为示例，返回false表示MD5值不存在
        return false;
    }

    // 保存MD5值到文件系统的函数
    private function saveMd5ToFilesystem($md5) {
        // 这里可以添加你的逻辑来保存MD5值到文件系统
        // 例如，你可以将MD5值写入到文件或数据库中
        // 这里仅为示例，不执行任何操作
    }

	// http://localhost:8010/tp6/public/PersonController/export
    public function export()
    {
        $persons = PersonModel::where('exported', false)->select();

        // 标记已导出的人员
        foreach ($persons as $person) {
            $person->exported = true;
            $person->save();
        }

        // 导出逻辑，生成CSV文件并下载
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="persons.csv"');

        $fp = fopen('php://output', 'w');
        fputcsv($fp, ['ID', 'Name', 'Picture', 'Tel', 'Dep', 'Exported']);

        foreach ($persons as $person) {
            fputcsv($fp, [
                $person->id,
                $person->name,
                // $person->picture,
                $person->tel,
                $person->dep,
                $person->exported ? 'Yes' : 'No'
            ]);
        }

        fclose($fp);
        exit;
    }

	public function getImage()
	{
		// 模拟从内存中获取图片数据
		$imageData = base64_encode(file_get_contents('path/to/your/image.jpg'));

		// 设置响应头信息，指定内容类型为图片
		$response = Response::create($imageData, 'image/jpeg');

		// 设置响应头信息，添加图片数据为base64编码
		$response->header('Content-Disposition', 'inline; filename="image.jpg"');
		$response->header('Content-Transfer-Encoding', 'base64');

		// 返回响应
		return $response;
	}

	public function getImageStream()
	{
		// 模拟从某处获取图片数据，这里可以是数据库、文件或其他数据源
		$imageData = file_get_contents('path/to/your/image.jpg');

		// 创建一个Stream响应
		$response = new Response($imageData, 'image/jpeg', 200);

		// 设置响应头，指示这是一个文件下载
		$response->header('Content-Disposition', 'attachment; filename="image.jpg"');

		// 返回Stream响应
		return $response;
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
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
