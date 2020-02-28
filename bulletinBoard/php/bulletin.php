<?php

    /*
        公告類型編號對應：
            +-------+--------+
            | Type  |  Name  |
            +-------+--------|
            |  0    |  全部   |
            |  1    |  活動   |
            |  2    |  宣導   |
            |  3    |  公告   |
            |  4    |  徵人   |
            |  5    |  其他   |
            +-------+--------+
     */

    include_once('debug.php');

    class BulletinType
    {
        // Variable.
        static public $kMaxType = 5;

        // Constructor.
        public function __construct()
        {
            // Create object instance is not supported.
            fatalErrorReport('Reate instance of BulletinType is not allowed.');
        }

        // Method.
        static public function numberToName($number)
        {
            switch((int)$number)
            {
                case 0:
                    return '全部';

                case 1:
                    return '活動';

                case 2:
                    return '宣導';

                case 3:
                    return '公告';

                case 4:
                    return '徵人';

                case 5:
                    return '其他';

                default:
                    return '未知';
            }
        }
    }

