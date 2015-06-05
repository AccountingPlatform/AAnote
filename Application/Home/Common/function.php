<?php

/**
 * 公共函数
 */
function outputListData($data) {
    if (is_array($data)) {
        outputData(array(
            'total' => count($data),
            'rows' => $data,
        ));
    } else {
        outputData($data);
    }
}

function outputData($data) {
    echo json_encode($data);
}
