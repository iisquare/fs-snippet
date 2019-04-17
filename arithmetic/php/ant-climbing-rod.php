<?php
/**
 * ��һ��27���׵�ϸľ�ˣ��ڵ�3���ס�7���ס�11���ס�17���ס�23���������λ���ϸ���һֻ���ϡ�
 * ľ�˺�ϸ������ͬʱͨ��һֻ���ϡ���ʼ ʱ�����ϵ�ͷ�����ǳ���������ģ�����ֻ�ᳯǰ�߻��ͷ��
 * ��������ˡ���������ֻ������ͷʱ����ֻ���ϻ�ͬʱ��ͷ���������ߡ�����������ÿ���ӿ�����һ���׵ľ��롣
 * ��д�������������϶��뿪ľ�� ����Сʱ������ʱ�䡣
 */
function add2($directionArr, $count, $i) {
	if(0 > $i) { // �������㷶Χ
		return $directionArr;
	}
	if(0 == $directionArr[$i]) { // ��ǰλ��1
		$directionArr[$i] = 1;
		return $directionArr;
	}
	$directionArr[$i] = 0;
	return add2($directionArr, $count, $i - 1); // ��λ
}

$positionArr = array( // ����λ��
	3,
	7,
	11,
	17,
	23
);

function path($positionArr) { // ���ɲ���·��
	$pathCalculate = array();
	$count = count($positionArr);
	$directionArr = array_fill(0, $count, 0); // ����
	$end = str_repeat('1', $count);
	while (true) {
		$path = implode('', $directionArr);
		$pathArray = array_combine($positionArr, $directionArr);
		$total = calculate($positionArr, $directionArr);
		$pathCalculate['P'.$path] = $total;
		if($end == $path) { // �������
			break;
		}
		$directionArr = add2($directionArr, $count, $count - 1);
	}
	return $pathCalculate;
}

function calculate($positionArr, $directionArr) {
	$total = 0; // ����ʱ
	$length = 27; // ľ�˳���
	while ($positionArr) {
		$total++; // ������ʱ
		$nextArr = array(); // ��һ��λ��
		foreach ($positionArr as $key => $value) {
			if(0 == $directionArr[$key]) {
				$next = $value - 1; // ��0������һ��
			} else {
				$next = $value + 1; // ��1������һ��
			}
			if(0 == $next) { // ��0�����߳�
				continue;
			}
			if($length == $next) { // ��1�����߳�
				continue;
			}
			$nextArr[$key] = $next;
		}
		$positionArr = $nextArr; // ��$positionArr��Ϊ��ʱ����������
		foreach ($nextArr as $key => $value) {
			$findArr = array_keys($positionArr, $value);
			if(count($findArr) < 2) { // û���غϵ�λ��
				continue ;
			} 
			foreach ($findArr as $findIndex) {
				$directionArr[$findIndex] = $directionArr[$findIndex] ? 0 : 1; // ������
				unset($positionArr[$findIndex]); // ��ֹ�ظ����Ҽ���
			}
		}
		$positionArr = $nextArr; // ��$positionArr��Ϊ��һ���������
	}
	return $total;
}

$pathCalculate = path($positionArr);
echo '<pre>calculate-';
print_r($pathCalculate);
echo 'sort-';
asort($pathCalculate);
print_r($pathCalculate);