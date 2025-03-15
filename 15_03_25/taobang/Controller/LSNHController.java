package com.example.taobang.Controller;

import com.example.taobang.DTO.ApiResponse;
import com.example.taobang.DTO.LSNHRequest;
import com.example.taobang.Entity.TableLaiSuatNganHang;
import com.example.taobang.Service.LSNHService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@RequestMapping("/lsnh")
public class LSNHController {
    @Autowired
    LSNHService lsnhService;
    @PostMapping("/create")
    public ApiResponse<TableLaiSuatNganHang> createTableLaiSuatNganHang(@RequestBody LSNHRequest request){
        return ApiResponse.<TableLaiSuatNganHang>builder().data(lsnhService.createTableLaiSuatNganHang(request)).build();
    }
    @PutMapping("/update/{id}")
    public ApiResponse<TableLaiSuatNganHang> updateTableLaiSuatNganHang(@PathVariable int id, @RequestBody LSNHRequest request){
        return ApiResponse.<TableLaiSuatNganHang>builder().data(lsnhService.updateTableLaiSuatNganHang(id,request)).build();
    }
    @GetMapping
    public ApiResponse<List<TableLaiSuatNganHang>> getAll(){
        return ApiResponse.<List<TableLaiSuatNganHang>>builder().data(lsnhService.getAll()).build();
    }
    @GetMapping("/{id}")
    public ApiResponse<TableLaiSuatNganHang> getTableLaiSuatNganHangbyID(@PathVariable int id){
        return ApiResponse.<TableLaiSuatNganHang>builder().data(lsnhService.getTableLaiSuatNganHangbyID(id)).build();
    }
    @DeleteMapping("/delete/{id}")
    public ApiResponse<String> deleteTableLaiSuatNganHang(@PathVariable String id){
        lsnhService.deleteTableLaiSuatNganHang(id);
        return ApiResponse.<String>builder().data(lsnhService.deleteTableLaiSuatNganHang(id)).build();
    }
}
