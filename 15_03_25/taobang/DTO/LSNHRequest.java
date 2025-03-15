package com.example.taobang.DTO;

import lombok.*;
import lombok.experimental.FieldDefaults;

@Data
@FieldDefaults(level = AccessLevel.PRIVATE)
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class LSNHRequest {
    String bankName;
    double threemonths;
    double sixmonths;
    double twelvemonths;
    double haitumonths;
}
