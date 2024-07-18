//#include "DHT.h"
#include <math.h>
#include <SoftwareSerial.h>

const int volPin1 = A0, volPin2 = A1;
const int curPin1 = A2, curPin2 = A3;
const int ldrPin1 = A4, ldrPin2 = A5;
const int ntcPin1 = A6, ntcPin2 = A7;

SoftwareSerial espSerial(7, 8);  //RX TX
String str1, str2;

char unit[6][5] = { " V ", " A ", "°C ", " % ", "W ", "Wh " };
char title[2][25] = { "TRADITIONAL SOLAR PANEL", "FLOATING SOLAR PANEL" };
char parameter[6][20] = { "Voltage: ", "Current: ", "Temp: ", "Light: ", "Power: ", "Energy: " };

unsigned long timeChanged = 0, previousTime = 0;
double e1 = 0, e2 = 0, p1 = 0, p2 = 0, calcE1 = 0, calcE2 = 0;
int delayTime = 2000;

double average(int pin) {
  double total = 0, sample = 2000;
  for (int x = 0; x < sample; x++) {
    double value = analogRead(pin);
    total += value;
  }
  double averageValue = total / sample;
  return averageValue;
}
double calibrate(int pin) {
  double calibratedValue = 0, lastCal = 0, x = average(pin);
  if (pin == volPin1) calibratedValue = (x * 21.1234 / 1023);
  else if (pin == volPin2) calibratedValue = (x * 21.2955 / 1023);
  else if (pin == curPin1) {
    calibratedValue = ((x * 10 / 1023) - 4.985) * 2.6;
    if (calibratedValue < 0) calibratedValue = 0;
  } else if (pin == curPin2) {
    calibratedValue = ((x * 10 / 1023) - 5) * 2.1852;
    if (calibratedValue < 0) calibratedValue = 0;
  } else if (pin == ntcPin1) calibratedValue = (((1023 - x) * 230 / 1023) - 80) * 0.3859675421;
  else if (pin == ntcPin2) calibratedValue = (((1023 - x) * 230 / 1023) - 80) * 0.3666156762;
  else if (pin == ldrPin1) calibratedValue = x / 1023 * 100;  //4.15756 * pow(x, (0.000575239 * x + 1.41314));
  else if (pin == ldrPin2) calibratedValue = x / 1023 * 100;  //12.0465 * pow(x, (0.00051527 * x + 1.25918));
  return calibratedValue;
}
void display(double value[2][6]) {
  Serial.println("Traditional Solar Panel: ");
  for (int i = 0; i < 6; i++) {
    Serial.print(parameter[i]);
    Serial.print(value[0][i], 5);
    Serial.println(unit[i]);
  }
  Serial.println();
  Serial.println("Floating Solar Panel: ");
  for (int i = 0; i < 6; i++) {
    Serial.print(parameter[i]);
    Serial.print(value[1][i], 5);
    Serial.println(unit[i]);
  }
}
void calc(double v1, double v2, double c1, double c2) {
  unsigned long currentTime = millis();
  if ((currentTime - previousTime) > 1000) {
    p1 = v1 * c1;
    p2 = v2 * c2;
    calcE1 += p1, calcE2 += p2;
    e1 = calcE1 / 3600.0;
    e2 = calcE2 / 3600.0;
    previousTime = currentTime;
  }
}
void setup() {
  Serial.begin(9600);
  espSerial.begin(9600);
  delay(10);
  timeChanged = millis();
}
void loop() {
  double v1 = calibrate(volPin1), v2 = calibrate(volPin2);
  double c1 = calibrate(curPin1), c2 = calibrate(curPin2);
  double t1 = calibrate(ntcPin1), t2 = calibrate(ntcPin2);
  double l1 = calibrate(ldrPin1), l2 = calibrate(ldrPin2);
  totalEnergy(v1, v2, c1, c2);
  if ((millis() - timeChanged) > delayTime) {
    double measurementValue[2][6] = { { v1, c1, t1, l1, p1, e1 }, { v2, c2, t2, l2, p2, e2 } };
    //display(measurementValue);
    str1 = String("v1=") + String(v1) + String("&c1=") + String(c1) + String("&l1=") + String(l1) + String("&t1=") + String(t1) + String("&p1=") + String(p1) + String("&e1=") + String(e1) + String("&v2=") + String(v2) + String("&c2=") + String(c2) + String("&l2=") + String(l2) + String("&t2=") + String(t2) + String("&p2=") + String(p2) + String("&e2=") + String(e2) + String("\n");
    //Serial.println(espSerial.available());
    if (espSerial.available() > 0) { espSerial.write(str1.c_str()); }
    timeChanged = millis();
  }
}