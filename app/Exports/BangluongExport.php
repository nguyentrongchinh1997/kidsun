<?php



namespace App\Exports;



use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithHeadings;

use Maatwebsite\Excel\Concerns\WithMapping;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Maatwebsite\Excel\Concerns\WithEvents;

use Maatwebsite\Excel\Events\AfterSheet;

use Maatwebsite\Excel\Concerns\FromArray;



class BangluongExport implements FromArray,WithHeadings,ShouldAutoSize, WithEvents

{

    protected $users;



    public function __construct(array $users)

    {

        $this->users = $users;

    }



    public function array(): array

    {

        return $this->users;

    }

    public function headings(): array {

        return [

            'Họ tên',

            'ID',

            'SỐ ĐIỆN THOẠI',

            'SỐ TIỀN',

            'CẤP BẬC',

            'SỐ TÀI KHOẢN',

            'CHỦ TÀI KHOẢN',

            'NGÂN HÀNG',

           	'CHI NHÁNH',

        ];

    }



    public function registerEvents(): array

    {

        return [

            AfterSheet::class => function (AfterSheet $event) {

                $cellRange = 'A1:I1'; // All headers

                $styleArray = [

                    'fill' => [

                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,

                        

                        'startColor' => [

                            'argb' => 'f26824',

                        ],

                        'endColor' => [

                            'argb' => 'f26824',

                        ],

                    ]

                ];

                $event->sheet->getStyle($cellRange)->getFont()->setSize(15);

                $event->sheet->getStyle($cellRange)->applyFromArray($styleArray);

            },

        ];

    }

}

