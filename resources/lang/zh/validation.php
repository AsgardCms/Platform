<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default error messages used by
	| the validator class. Some of these rules have multiple versions such
	| as the size rules. Feel free to tweak each of these messages here.
	|
	*/

	"accepted"             => "必须接受 :attribute .",
	"active_url"           => ":attribute 不是有效的URL.",
	"after"                => ":attribute 必须为 :date 之后的日期.",
	"alpha"                => ":attribute 只允许字符.",
	"alpha_dash"           => ":attribute 仅允许包含字母, 数字, 以及下划线.",
	"alpha_num"            => ":attribute 仅允许包含字母和数字.",
	"array"                => ":attribute 必须为数组.",
	"before"               => ":attribute 必须为 :date 之前的日期.",
	"between"              => [
		"numeric" => ":attribute 必须在 :min 和 :max 之间.",
		"file"    => ":attribute 必须在 :min Kb 和 :max Kb 之间.",
		"string"  => ":attribute 字数必须在 :min 和 :max 之间.",
		"array"   => ":attribute 项目必须含有 :min 和 :max 之间的项目数量.",
	],
	"boolean"              => ":attribute 仅允许 true 或 false.",
	"confirmed"            => ":attribute 两次输入不匹配.",
	"date"                 => ":attribute 不是一个有效日期.",
	"date_format"          => ":attribute 不匹配格式 :format.",
	"different"            => ":attribute 和 :other 必须不同.",
	"digits"               => ":attribute 必须为 :digits 数字.",
	"digits_between"       => ":attribute 必须为 :min 和 :max 之间的数字.",
	"email"                => ":attribute 必须为有效的电子邮件地址.",
	"filled"               => ":attribute 为必填.",
	"exists"               => "已选择的 :attribute 不可用.",
	"image"                => ":attribute 必须为图像.",
	"in"                   => "已选择的 :attribute 不可用.",
	"integer"              => ":attribute 必须为数字.",
	"ip"                   => ":attribute 必须为有效的IP地址.",
	"max"                  => [
		"numeric" => ":attribute 不能大于 :max.",
		"file"    => ":attribute 不能大于 :max Kb.",
		"string"  => ":attribute 不能多于 :max 字符.",
		"array"   => ":attribute 不能包含超过 :max 项目.",
	],
	"mimes"                => ":attribute 的文件类型必须为: :values.",
	"min"                  => [
		"numeric" => ":attribute 不能少于 :min.",
		"file"    => ":attribute 必须大于 :min Kb.",
		"string"  => ":attribute 必须含有至少 :min 字符.",
		"array"   => ":attribute 必须含有至少 :min 项目.",
	],
	"not_in"               => "已选择的 :attribute 不可用.",
	"numeric"              => ":attribute 必须为数字.",
	"regex"                => ":attribute 格式错误.",
	"required"             => ":attribute 为必填.",
	"required_if"          => "当 :other 为 :value 时, :attribute 为必填.",
	"required_with"        => "当 :values 设置时 :attribute 字段为必填.",
	"required_with_all"    => "当所有 :values 均设置时 :attribute 字段为必填.",
	"required_without"     => "当 :values 未设置时 :attribute 字段为必填.",
	"required_without_all" => "当所有 :values 均未设置时 :attribute 字段为必填.",
	"same"                 => ":attribute 和 :other 必须一致.",
	"size"                 => [
		"numeric" => ":attribute 必须为 :size.",
		"file"    => ":attribute 必须为 :size Kb.",
		"string"  => ":attribute 必须为  :size 字符.",
		"array"   => ":attribute 必须为含有 :size 项目.",
	],
	"unique"               => ":attribute 已使用.",
	"url"                  => ":attribute 格式错误.",
	"timezone"             => ":attribute 必须为有效时区.",

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using the
	| convention "attribute.rule" to name the lines. This makes it quick to
	| specify a specific custom language line for a given attribute rule.
	|
	*/

	'custom' => [
		'attribute-name' => [
			'rule-name' => '自定义信息',
		],
	],

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap attribute place-holders
	| with something more reader friendly such as E-Mail Address instead
	| of "email". This simply helps us make messages a little cleaner.
	|
	*/

	'attributes' => [],

];
