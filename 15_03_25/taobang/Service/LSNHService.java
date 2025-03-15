package com.example.taobang.Service;

import com.example.taobang.DTO.LSNHRequest;
import com.example.taobang.Entity.TableLaiSuatNganHang;
import com.example.taobang.Repository.LSNHRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.List;

@Service
public class LSNHService {
    @Autowired
    LSNHRepository lsnhRepository;

    public TableLaiSuatNganHang createTableLaiSuatNganHang(LSNHRequest request){
        TableLaiSuatNganHang tableLaiSuatNganHang = TableLaiSuatNganHang.builder()
                .bankName(request.getBankName())
                .threemonths(request.getThreemonths())
                .sixmonths(request.getSixmonths())
                .twelvemonths(request.getTwelvemonths())
                .haitumonths(request.getHaitumonths())
                .build();
        lsnhRepository.save(tableLaiSuatNganHang);
        return tableLaiSuatNganHang;
    }

    public TableLaiSuatNganHang updateTableLaiSuatNganHang(int id, LSNHRequest request) {
        TableLaiSuatNganHang tb = lsnhRepository.findById(id);
        if(tb == null){
            throw new IllegalArgumentException("Not found");
        }
        tb.setBankName(request.getBankName());
        tb.setThreemonths(request.getThreemonths());
        tb.setSixmonths(request.getSixmonths());
        tb.setTwelvemonths(request.getTwelvemonths());
        tb.setHaitumonths(request.getHaitumonths());
        lsnhRepository.save(tb);
        return tb;
    }
    public TableLaiSuatNganHang getTableLaiSuatNganHangbyID(int id){
        return lsnhRepository.findById(id);
    }
    public List<TableLaiSuatNganHang> getAll(){
        return lsnhRepository.findAll();
    }
    public String deleteTableLaiSuatNganHang(String id){
        lsnhRepository.deleteById(id);
        return "Deleted";
    }
}
