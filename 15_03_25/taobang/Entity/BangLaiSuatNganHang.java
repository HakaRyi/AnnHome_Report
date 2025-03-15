package com.example.taobang.Entity;

import jakarta.persistence.Entity;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import lombok.*;
import lombok.experimental.FieldDefaults;

@Entity
@Data
@FieldDefaults(level = AccessLevel.PRIVATE)
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class BangLaiSuatNganHang {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    int id;
    String bankName;
    double _3Months;
    double sixmonths;
    double twelvemonths;
    double haitumonths;
}
