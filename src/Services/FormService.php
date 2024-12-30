<?php


namespace lz\admin\Services;


use lz\admin\Traits\ResTrait;

class FormService
{

    use ResTrait;

    /**
     * @param $category
     * @param $title
     * @param $field
     * @param $is_block
     * @param $value
     * @param $required
     * @param $type
     * @param $range
     * @param $option
     * @param $custom_class
     * @param $ban_edit
     * @return string
     */
    public static function formRender($category, $title, $field, $is_block, $value, $required, $type, $range, $option, $custom_class, $ban_edit)
    {
        switch ($category) {
            case 'textarea':
                $html = FormService::textarea($title, $field, $is_block, $value, $required, $custom_class, $ban_edit);
                break;
            case 'select':
                $html = FormService::select($title, $field, $option, $is_block, $value, $required, $custom_class, $ban_edit);
                break;
            case 'radio':
                $html = FormService::radio($title, $field, $option, $is_block, $value, $required, $custom_class, $ban_edit);
                break;
            case 'checkbox':
                $html = FormService::checkbox($title, $field, $option, $is_block, $value, $required, $custom_class, $ban_edit);
                break;
            case 'layDate':
                $html = FormService::layDate($title, $field, $type, $range, $is_block, $value, $required, $custom_class, $ban_edit);
                break;
            case 'imageUpload':
                $html = FormService::imageUpload($title, $field, $is_block, $value, $required, $custom_class, $ban_edit);
                break;
            case 'imageUploadMultiple':
                $html = FormService::imageUploadMultiple($title, $field, $is_block, $value, $required, $custom_class, $ban_edit);
                break;
            case 'editor':
                $html = FormService::editor($title, $field, $is_block, $value, $required, $custom_class, $ban_edit);
                break;
            case 'file':
                $html = FormService::file($title, $field, $is_block, $value, $required, $custom_class, $ban_edit);
                break;
            default:
                $html = FormService::input($title, $field, $is_block, $value, $required, $type, $custom_class, $ban_edit);
                break;
        }
        return $html;
    }

    /**
     * 单行文本框
     * @param $title
     * @param $field
     * @param bool $is_block
     * @param null $value
     * @param bool $required
     * @param string $type
     * @param string $custom_class
     * @param string $ban_edit
     * @return string
     */
    public static function input($title, $field, $is_block = false, $value = null, $required = false, $type = 'text', $custom_class = '', $ban_edit = '')
    {
        $temp = "<div class='layui-form-item'>";
        $temp .= "<label class='layui-form-label'>";
        if ($required) {
            $temp .= "<span class='required_option'>*</span>";
        }
        $temp .= $title . '：</label>';
        $class = ($is_block ? 'layui-input-block' : 'layui-input-inline');
        $temp .= "<div class='{$class} {$custom_class}'>";
        $temp .= "<input type='$type' name='$field' value='$value'";
        if ($required) {
            $temp .= " required lay-verify='required'";
        }
        if (!empty($ban_edit)) {
            $temp .= ' readonly';
        }
        $temp .= " class='layui-input' autocomplete='off'>";
        $temp .= '</div></div>';
        return $temp;
    }

    /**
     * 多行文本框
     * @param $title
     * @param $field
     * @param bool $is_block
     * @param null $value
     * @param bool $required
     * @param string $custom_class
     * @param string $ban_edit
     * @return string
     */
    public static function textarea($title, $field, $is_block = false, $value = null, $required = false, $custom_class = '', $ban_edit = '')
    {
        $temp = "<div class='layui-form-item'>";
        $temp .= "<label class='layui-form-label'>";
        if ($required) {
            $temp .= "<span class='required_option'>*</span>";
        }
        $temp .= $title . '：</label>';
        $class = ($is_block ? 'layui-input-block' : 'layui-input-inline');
        $temp .= "<div class='{$class} {$custom_class}'>";
        $temp .= "<textarea name='$field' value='$value'";
        if ($required) {
            $temp .= " required lay-verify='required'";
        }
        if (!empty($ban_edit)) {
            $temp .= ' readonly';
        }
        $temp .= " class='layui-textarea' autocomplete='off'>" . $value . "</textarea>";
        $temp .= '</div></div>';
        return $temp;
    }


    /**
     * 下拉选项
     * @param $title
     * @param $field
     * @param array $options
     * @param bool $is_block
     * @param null $value
     * @param bool $required
     * @param string $custom_class
     * @param string $ban_edit
     * @return string
     */
    public static function select($title, $field, $options = [], $is_block = false, $value = null, $required = false, $custom_class = '', $ban_edit = '')
    {
        $temp = "<div class='layui-form-item'>";
        $temp .= "<label class='layui-form-label'>";
        $required_text = '';
        if ($required) {
            $required_text = "required lay-verify='required'";
            $temp .= "<span class='required_option'>*</span>";
        }
        $temp .= $title . '：</label>';
        $class = ($is_block ? 'layui-input-block' : 'layui-input-inline');
        $temp .= "<div class='{$class} {$custom_class}'>";
        if (!empty($ban_edit)) {
            $temp .= "<select disabled name='$field' {$required_text}>";
        } else {
            $temp .= "<select name='$field' {$required_text}>";
        }
        $temp .= "<option value=''>请选择</option>";
        foreach ($options as $option) {
            $temp .= "<option value='{$option['value']}' " . (($option['value'] !== '' && $option['value'] == $value) ? 'selected' : '') . ">{$option['title']}</option>";
        }
        $temp .= '</select>';
        $temp .= '</div></div>';
        return $temp;
    }

    /**
     * 单选框
     * @param $title
     * @param $field
     * @param array $options
     * @param bool $is_block
     * @param null $value
     * @param bool $required
     * @param string $custom_class
     * @param string $ban_edit
     * @return string
     */
    public static function radio($title, $field, $options = [], $is_block = false, $value = null, $required = false, $custom_class = '', $ban_edit = '')
    {
        $temp = "<div class='layui-form-item'>";
        $temp .= "<label class='layui-form-label'>";
        $required_text = '';
        if ($required) {
            $required_text = "required lay-verify='required'";
            $temp .= "<span class='required_option'>*</span>";
        }
        $temp .= $title . '：</label>';
        $class = ($is_block ? 'layui-input-block' : 'layui-input-inline');
        $temp .= "<div class='{$class} {$custom_class}'>";
        foreach ($options as $option) {
            $temp .= '<input type="radio" ' . (!empty($ban_edit) ? ' disabled ' : '') . $required_text . ' name="' . $field . '" value="' . $option['value'] . '" title="' . $option['title'] . '" ' . (($option['value'] !== '' && $option['value'] == $value) ? 'checked' : '') . '>';
        }
        $temp .= '</select>';
        $temp .= '</div></div>';
        return $temp;
    }

    /**
     * 复选框
     * @param $title
     * @param $field
     * @param array $options
     * @param bool $is_block
     * @param array $value
     * @param bool $required
     * @param string $custom_class
     * @param string $ban_edit
     * @return string
     */
    public static function checkbox($title, $field, $options = [], $is_block = false, $value = [], $required = false, $custom_class = '', $ban_edit = '')
    {
        $value = (array)$value;
        $temp = "<div class='layui-form-item'>";
        $temp .= "<label class='layui-form-label'>";
        $required_text = "";
        if ($required) {
            $required_text = "required lay-verify='required'";
            $temp .= "<span class='required_option'>*</span>";
        }
        $temp .= $title . '：</label>';
        $class = ($is_block ? 'layui-input-block' : 'layui-input-inline');
        $temp .= "<div class='{$class} {$custom_class}'>";

        foreach ($options as $option) {
            $temp .= '<input type="checkbox" ' . (!empty($ban_edit) ? ' disabled ' : '') . $required_text . ' name="' . $field . '[]" value="' . $option['value'] . '" title="' . $option['title'] . '" ' . (in_array($option['value'], $value) ? 'checked' : '') . '>';
        }
        $temp .= '</select>';
        $temp .= '</div></div>';
        return $temp;
    }


    /**
     * 日期组件
     * @param $title
     * @param $field
     * @param string $type
     * @param bool $range
     * @param bool $is_block
     * @param null $value
     * @param bool $required
     * @param string $custom_class
     * @param string $ban_edit
     * @return string
     */
    public static function layDate($title, $field, $type = 'date', $range = false, $is_block = false, $value = null, $required = false, $custom_class = '', $ban_edit = '')
    {
        $temp = "<div class='layui-form-item'>";
        $temp .= "<label class='layui-form-label'>";
        if ($required) {
            $temp .= "<span class='required_option'>*</span>";
        }
        $temp .= $title . '：</label>';
        $class = ($is_block ? 'layui-input-block' : 'layui-input-inline');
        $temp .= "<div class='{$class} {$custom_class}'>";
        $temp .= "<input type='text' name='$field' value='$value'";
        if ($required) {
            $temp .= " required lay-verify='required'";
        }
        if (!empty($ban_edit)) {
            $temp .= " readonly class='layui-input'>";
        } else {
            $temp .= " class='layui-input customer-layDate-obj' autocomplete='off' data-type='$type'  data-range='$range'>";
        }
        $temp .= '</div></div>';
        return $temp;
    }

    /**
     * 单图上传
     * @param $title
     * @param $field
     * @param bool $is_block
     * @param null $value
     * @param bool $required
     * @param string $custom_class
     * @param string $ban_edit
     * @return string
     */
    public static function imageUpload($title, $field, $is_block = false, $value = null, $required = false, $custom_class = '', $ban_edit = '')
    {
        $temp = "<div class='layui-form-item'>";
        $temp .= "<label class='layui-form-label'>";
        if ($required) {
            $temp .= "<span class='required_option'>*</span>";
        }
        $temp .= $title . '：</label>';
        $class = ($is_block ? 'layui-input-block' : 'layui-input-inline');
        $temp .= "<div class='{$class} {$custom_class}  image-show-box'>";
        $temp .= '<div class="uploadImage">';
        $temp .= "<input type='hidden' name='{$field}' value='{$value}'";
        if ($required) {
            $temp .= " required lay-verify='required'";
        }
        $temp .= '>';
        $default_hide = '';
        if (!empty($value)) {
            $default_hide = "style='display: none'";
        }
        $temp .= " <div class='layui-upload-drag upload' {$default_hide}><i class='layui-icon'></i><p>点击上传，或将文件拖拽到此处</p></div>";
        $image_hide = '';
        if (empty($value)) {
            $image_hide = 'style="display: none"';
        }
        $temp .= "<div class='image' {$image_hide}>";
        $temp .= "<img src='{$value}'>";
        if (empty($ban_edit)) {
            $temp .= ' <div class="btn"><a class="layui-btn layui-btn-sm upload">更换</a><a class="layui-btn layui-btn-sm layui-btn-danger delete">删除</a></div>';
        }
        $temp .= '</div></div></div></div>';
        return $temp;
    }

    /**
     * 多图上传
     * @param $title
     * @param $field
     * @param bool $is_block
     * @param array $value
     * @param bool $required
     * @param string $custom_class
     * @param string $ban_edit
     * @return string
     */
    public static function imageUploadMultiple($title, $field, $is_block = false, $value = [], $required = false, $custom_class = '', $ban_edit = '')
    {
        if (is_string($value)) {
            $value = [];
        }
        $temp = "<div class='layui-form-item'>";
        $temp .= "<label class='layui-form-label'>";
        if ($required) {
            $temp .= "<span class='required_option'>*</span>";
        }
        $temp .= $title . '：</label>';
        $class = ($is_block ? 'layui-input-block' : 'layui-input-inline');
        $temp .= "<div class='{$class} {$custom_class} image-show-box'>";
        if (empty($ban_edit)) {
            $temp .= '<a class="layui-btn uploadImageMultipleBtn" data-field="' . $field . '[]">选择图片</a>';
        }
        $temp .= '<div class="uploadImageMultiple">';
        foreach ($value as $val) {
            $temp .= '<div class="uploadImage">';
            $temp .= "<input type='hidden' name='{$field}[]' value='{$val}'>";
            $temp .= "<div class='image'>";
            $temp .= "<img src='{$val}'>";
            if (empty($ban_edit)) {
                $temp .= ' <div class="btn"><a class="layui-btn layui-btn-sm upload">更换</a><a class="layui-btn layui-btn-sm layui-btn-danger delete"   data-status="true">删除</a></div>';
            }
            $temp .= '</div></div>';
        }
        $temp .= '</div></div></div>';
        return $temp;
    }

    /**
     * 富文本编辑器
     * @param $title
     * @param $field
     * @param bool $is_block
     * @param null $value
     * @param bool $required
     * @param string $custom_class
     * @param string $ban_edit
     * @return string
     */
    public static function editor($title, $field, $is_block = false, $value = null, $required = false, $custom_class = '', $ban_edit = '')
    {
        $temp = "<div class='layui-form-item'>";
        $temp .= "<label class='layui-form-label'>";
        if ($required) {
            $temp .= "<span class='required_option'>*</span>";
        }
        $temp .= $title . '：</label>';
        $class = ($is_block ? 'layui-input-block' : 'layui-input-inline');
        $temp .= "<div class='{$class} {$custom_class}'>";
        $temp .= '<div class="editor—wrapper" data-edit="' . $ban_edit . '">';
        $temp .= '<textarea class="layui-hide" name="' . $field . '">' . $value . '</textarea>';
        $temp .= '<div class="toolbar-container"></div><div class="editor-container"></div>';
        $temp .= '</div></div></div>';
        return $temp;
    }

    /**
     * 文件上传
     * @param $title
     * @param $field
     * @param bool $is_block
     * @param null $value
     * @param bool $required
     * @param string $custom_class
     * @param string $ban_edit
     * @return string
     */
    public static function file($title, $field, $is_block = false, $value = null, $required = false, $custom_class = '', $ban_edit = '')
    {
        $temp = "<div class='layui-form-item'>";
        $temp .= "<label class='layui-form-label'>";
        if ($required) {
            $temp .= "<span class='required_option'>*</span>";
        }
        $temp .= $title . '：</label>';
        $class = ($is_block ? 'layui-input-block' : 'layui-input-inline');
        $temp .= "<div class='{$class} {$custom_class} uploadFile'>";
        $temp .= '<a type="button" class="layui-btn upload"><i class="layui-icon"></i>上传文件</a>';
        $temp .= "<input type='text' name='$field' style='width: calc(100% - 120px);float: right;' value='$value'";
        if ($required) {
            $temp .= " required lay-verify='required'";
        }
        if (!empty($ban_edit)) {
            $temp .= ' readonly';
        }
        $temp .= " class='layui-input' autocomplete='off'>";
        $temp .= '</div></div></div>';
        return $temp;
    }
}
