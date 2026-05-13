<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use PhpOffice\PhpSpreadsheet\IOFactory;

use App\Models\Pair;

class PairController extends BaseController
{
    public function index()
    {
        return view('admin.pages.pairs.bio');
    }

    public function bio(Request $request)
    {
        return view('admin.pages.pairs.bio');
    }

    public function chakra()
    {
        return view('admin.pages.pairs.chakra');
    }

    public function import(Request $request)
    {
        return view('admin.pages.pairs.import', compact('request'));
    }

    public function parse(Request $request)
    {
        $uploadOk       = 1;
        $msgStr         = "";
        $target_dir     = "uploads/";
        $target_file    = $target_dir . basename($_FILES["pair_file"]["name"]);
        $fileType       = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if file already exists
        if (file_exists($target_file)) {
            $msgStr = "Sorry, file already exists.";
            $uploadOk = 0;
            return redirect()->to('/admin/pairs/import')->with('message.fail', $msgStr);
        }

        // Allow certain file formats
        if ($fileType != "xls" && $fileType != "xlsx") {
            $msgStr     =  "Sorry, only XLS and XLSX files are allowed.";
            $uploadOk   = 0;
            return redirect()->to('/admin/pairs/import')->with('message.fail', $msgStr);
        } else {
            if (move_uploaded_file($_FILES["pair_file"]["tmp_name"], $target_file)) {
                $msgStr = "The file ". basename($_FILES["pair_file"]["name"]). " has been uploaded.";
            } else {
                $uploadOk = 0;
                $msgStr   = "Sorry, there was an error uploading your file.";
                return redirect()->to('/admin/pairs/import')->with('message.fail', $msgStr);
            }
        }

        if ($uploadOk > 0) {
            // Parse the file
            $pairsArray = $this->parseXLSFile($target_file);
            $scan_type = $request->input('scan_type');

            // Store parsed array to database
            if (count($pairsArray) > 0) {
                foreach ($pairsArray as $pval) {
                    if (!str_contains($pval[1], 'NAME') && !str_contains($pval[1], 'POINTS/NAME')) {
                        $pair = Pair::create([
                            'scan_type'         => $scan_type,
                            'ref_no'            => $pval[0],
                            'name'              => $pval[1],
                            'radical'           => $pval[2],
                            'origins'           => $pval[3],
                            'symptoms'          => $pval[4],
                            'paths'             => $pval[5],
                            'alternative_routes'=> $pval[6]
                        ]);
                    }
                }
            }
            
            //Delete file after successful parse and store in DB
            unlink($target_file);
        }

        return redirect()->to('/admin/pairs')->with('message.success', 'You have successfully parsed the pair file. See list below for your data.');
    }

    public function parseXLSFile($filepath = "")
    {
        $namedDataArray = array();

        if (!empty($filepath)) {
            $inputFileName  = $filepath;
            $inputFileType  = IOFactory::identify($inputFileName);
            $objReader      = IOFactory::createReader($inputFileType);
            $objPHPExcel    = $objReader->load($inputFileName);
            
            $sheetCnt       = $objPHPExcel->getSheetCount();
            $r              = -1;
            for ($sheet = 0; $sheet < $sheetCnt; $sheet++) {
                $objWorksheet   = $objPHPExcel->getSheet($sheet);

                //excel with first row header, use header as key
                $highestRow    = $objWorksheet->getHighestRow();
                $highestColumn = $objWorksheet->getHighestColumn();
                $headingsArray = $objWorksheet->rangeToArray('A1:'.$highestColumn.'1', null, true, true, true);
                $headingsArray = $headingsArray[1];
        
                for ($row = 2; $row <= $highestRow; ++$row) {
                    $dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row, null, true, true, true);
                    if ((isset($dataRow[$row]['A'])) && ($dataRow[$row]['A'] > '')) {
                        ++$r;
                        foreach ($headingsArray as $columnKey => $columnHeading) {
                            $namedDataArray[$r][] = $dataRow[$row][$columnKey];
                        }
                    }
                }
            }
        }

        return $namedDataArray;
    }
}
