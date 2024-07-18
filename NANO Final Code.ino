#include <math.h>
#include <Wire.h>
#include <SDL_Arduino_INA3221.h>
#include <hd44780.h>
#include <hd44780ioClass/hd44780_I2Cexp.h>
#include "DFRobot_AHT20.h"

DFRobot_AHT20 aht20;
SDL_Arduino_INA3221 ina3221;
hd44780_I2Cexp lcd;

const int BBVpin = A3, BTpin = A6, ldrPin = A7;
const int sw1 = 2, sw2 = 3, sw3 = 4, sw4 = 5, backlight = 12;
const int Q_fan = 6, Q_Panel = 7, Q_Batt_B = 11, Q_Inverter = 10, Q_Batt_M = 8, Q_SCC = 9;
float panelVoltage, panelCurrent, ambTemp, lightIntensity, batteryVoltage, batteryCurrent, batteryLevel, BBVoltage, batteryTemp, inverterVoltage, inverterCurrent, power, Etotal, Etoday, Pused, Eused, charge;
unsigned long calcPrevTime = 0, UARTprevTime = 0, InvTimeChanged = 0, battPrevTime = 0;
int UARTdelayTime = 30000, battDelayTime = 1000, calcDelayTime = 1000;
int btnOn = 0, prevBtnOn = 0, btnUp = 0, prevBtnUp = 0, btnDown = 0, prevBtnDown = 0, btnEnter = 0, prevBtnEnter = 0;
int fanState, inverterState, batt_bState, batt_mState, panelState, controllerState;
String data, controlStatus[5];
char dataReceived;
bool timesUp = false;
bool fan = false;
const int displayLines = 4, arrow = 4;
int startLine = 0, arrowLine = 0, LCDpage = 0;

#define PANEL_CHANNEL 2
#define INVERTER_CHANNEL 3
#define BATTERY_CHANNEL 1

String LCDmenu[4] = {
  "Solar Panel",
  "Battery",
  "Inverter",
  "Control Box",
};
String LCDsetting[8];

void updateLCDsetting() {
  LCDsetting[0] = "Fan : " + controlStatus[0];
  LCDsetting[1] = "Inv : " + controlStatus[1];
  LCDsetting[2] = "B_Batt: " + controlStatus[2];
  LCDsetting[3] = "M_Batt: " + controlStatus[3];
  LCDsetting[4] = "Panel : " + controlStatus[4];
  LCDsetting[5] = "Temp  : " + String(ambTemp) + (char)223 + "C";
  LCDsetting[6] = "LightInt :" + String(lightIntensity) + "%";
  LCDsetting[7] = "Back";
}

void updateDisplay(String display[]) {
  lcd.clear();
  lcd.setCursor(0, arrowLine);
  lcd.print(">");
  for (int i = 0; i < displayLines; i++) {
    lcd.setCursor(2, i);
    lcd.print(display[startLine + i]);
    lcd.setCursor(19, i);
    lcd.print((char)126);
  }
}
void scrollUp(String display[]) {
  if (arrowLine > 0) {
    arrowLine--;
  } else {
    if (startLine != 0) startLine--;
  }
  updateDisplay(display);
}
void scrollDown(String display[], int totalLine) {
  if (arrowLine < (displayLines - 1) && (startLine + arrowLine) < totalLine - 1) {
    arrowLine++;
  } else {
    if ((startLine + displayLines) < totalLine) startLine++;
  }
  updateDisplay(display);
}
void enter() {
  switch (startLine + arrowLine) {
    case 0:
      LCDpage = 1;
      lcd.clear();
      lcd.setCursor(0, 0);
      lcd.print("Power  : " + String(power) + "W");
      lcd.setCursor(0, 1);
      lcd.print("Etoday : " + String(Etoday) + "Wh");
      lcd.setCursor(0, 2);
      lcd.print("Etotal : " + String(Etotal) + "Wh");
      lcd.setCursor(0, 3);
      lcd.print("> Back");
      break;

    case 1:
      LCDpage = 2;
      lcd.clear();
      lcd.setCursor(0, 0);
      lcd.print("Batt Level:" + String(batteryLevel, 1) + "%");
      if(batteryTemp < 0){
        lcd.setCursor(0, 1);
        lcd.print("Temp: ERROR");
      } else {
        lcd.setCursor(0, 1);
        lcd.print("Temp: " + String(batteryTemp, 1) + (char)223 + "C");
      }
      lcd.setCursor(0, 2);
      lcd.print("> Back");
      break;

    case 2:
      LCDpage = 3;
      lcd.clear();
      lcd.setCursor(0, 0);
      lcd.print("Inverter");
      lcd.setCursor(0, 1);
      lcd.print("Eused  : " + String(Eused) + "Wh");
      lcd.setCursor(0, 2);
      lcd.print("Status : " + String(controlStatus[1]));
      lcd.setCursor(0, 3);
      lcd.print("> Back");
      break;

    case 3:
      arrowLine = 0, startLine = 0, LCDpage = 4;
      lcd.clear();
      lcd.setCursor(0, 0);
      lcd.print("Fan: " + String(controlStatus[0]));
      lcd.setCursor(9, 0);
      lcd.print("B_BATT: " + String(controlStatus[2]));
      lcd.setCursor(0, 1);
      lcd.print("FPV: " + String(controlStatus[4]));
      lcd.setCursor(9, 1);
      lcd.print("M_BATT: " + String(controlStatus[3]));
      if (lightIntensity == 0) {
        lcd.setCursor(0, 2);
        lcd.print("L : N/A");
      } else {
        lcd.setCursor(0, 2);
        lcd.print("L :" + String(lightIntensity, 1) + "%");
      }
      lcd.setCursor(9, 2);
      lcd.print("T : " + String(ambTemp, 1) + (char)223 + "C");
      lcd.setCursor(0, 3);
      lcd.print("> Back");
      /* updateLCDsetting();
      updateDisplay(LCDsetting); */
      break;

    default:
      return;
  }
}
void LCDdisplay() {
  btnOn = digitalRead(sw1);
  btnUp = digitalRead(sw2);
  btnDown = digitalRead(sw3);
  btnEnter = digitalRead(sw4);
  if (btnOn == 0 && prevBtnOn == 1 && LCDpage == 0) {
    digitalWrite(backlight, !digitalRead(backlight));
    updateDisplay(LCDmenu);
  } else if (btnOn == 0 && prevBtnOn == 1 && LCDpage == 4) {
    digitalWrite(backlight, !digitalRead(backlight));
    updateDisplay(LCDsetting);
  } else if (btnUp == 0 && prevBtnUp == 1 && LCDpage == 0) {
    scrollUp(LCDmenu);
    updateDisplay(LCDmenu);
  } else if (btnUp == 0 && prevBtnUp == 1 && LCDpage == 4) {
    scrollUp(LCDsetting);
    updateDisplay(LCDsetting);
  } else if (btnDown == 0 && prevBtnDown == 1 && LCDpage == 0) {
    scrollDown(LCDmenu, 4);
    updateDisplay(LCDmenu);
  } else if (btnDown == 0 && prevBtnDown == 1 && LCDpage == 4) {
    scrollDown(LCDsetting, 8);
    updateDisplay(LCDsetting);
  } else if (btnEnter == 0 && prevBtnEnter == 1 && LCDpage == 0) {
    enter();
  } else if (btnEnter == 0 && prevBtnEnter == 1 && (LCDpage == 1 || LCDpage == 2 || LCDpage == 3)) {
    arrowLine = 0, startLine = 0, LCDpage = 0;
    updateDisplay(LCDmenu);
  } else if (btnEnter == 0 && prevBtnEnter == 1 && LCDpage == 4) {  //&& startLine + arrowLine == 7
    arrowLine = 0, startLine = 0, LCDpage = 0;
    updateDisplay(LCDmenu);
  }
  prevBtnOn = btnOn;
  prevBtnUp = btnUp;
  prevBtnDown = btnDown;
  prevBtnEnter = btnEnter;
}
float getBBVoltage() {
  double total = 0, sample = 5000;
  for (int x = 0; x < sample; x++) {
    double value = analogRead(BBVpin);
    total += value;
  }
  double averageValue = total / sample;
  return ((averageValue * 21.5) / 1023.0) * 1.21;
}
void getReading() {
  if (aht20.startMeasurementReady(true)) ambTemp = aht20.getTemperature_C();
  batteryTemp = ((analogRead(BTpin) / 1023.0 * 165.0) - 40.0) * 0.5468;
  lightIntensity = analogRead(ldrPin) / 1023.0 * 100.0;
  BBVoltage = getBBVoltage();
  batteryVoltage = ina3221.getBusVoltage_V(BATTERY_CHANNEL);
  batteryCurrent = -ina3221.getCurrent_mA(BATTERY_CHANNEL) / 1000;  // minus is to get the "sense" right.   - means the battery is charging, + that it is discharging
  inverterVoltage = ina3221.getBusVoltage_V(INVERTER_CHANNEL);
  inverterCurrent = -ina3221.getCurrent_mA(INVERTER_CHANNEL) / 1000;
  panelVoltage = ina3221.getBusVoltage_V(PANEL_CHANNEL);
  panelCurrent = ina3221.getCurrent_mA(PANEL_CHANNEL) / 1000;
}
void getBatteryLevel() {
  charge -= batteryCurrent;
  batteryLevel = charge / 360;  // unit in percentage charge * 100 /10 * 3600
  if (batteryLevel > 100) batteryLevel = 100;
  else if (batteryLevel < 0) batteryLevel = 0;
  /*Serial.println("Current         : " + String(batteryCurrent));
  Serial.println("Battery Amp-hour: " + String(charge / 36000));
  Serial.println("Battery Level   : " + String(batteryLevel));*/
}
void calcPE() {
  power = panelVoltage * panelCurrent;
  Etoday += (power / 3600);
  Etotal += (power / 3600);

  Pused = inverterVoltage * inverterCurrent;
  if(Pused < 0) Pused = 0;
  Eused += (Pused / 3600);
}
void control() {
  //Serial.println(dataReceived);
  if (dataReceived == 'a') {
    digitalWrite(Q_Batt_M, digitalRead(Q_Batt_B));
    digitalWrite(Q_Batt_B, !digitalRead(Q_Batt_B));
    batt_bState = digitalRead(Q_Batt_B);
    batt_mState = digitalRead(Q_Batt_M);
    digitalWrite(Q_SCC, !digitalRead(Q_SCC));
    controllerState = digitalRead(Q_SCC);
  } else if (dataReceived == 'b') {
    digitalWrite(Q_Inverter, !digitalRead(Q_Inverter));
    inverterState = digitalRead(Q_Inverter);
  } else if (dataReceived == 'c') {
    digitalWrite(Q_Panel, !digitalRead(Q_Panel));
    panelState = digitalRead(Q_Panel);
  } else if (dataReceived == 'd') {
    fan = !fan;
    fanState = digitalRead(Q_fan);
  } else if (dataReceived == 'e') {
    //digitalWrite(Q_SCC, !digitalRead(Q_SCC));
    //controllerState = digitalRead(Q_SCC);
  } else if (dataReceived == 'f') Etoday = 0;

  fanState = digitalRead(Q_fan);
  if (fanState == 1) controlStatus[0] = "On";
  else controlStatus[0] = "Off";

  inverterState = digitalRead(Q_Inverter);
  if (inverterState == 1) controlStatus[1] = "On";
  else controlStatus[1] = "Off";

  batt_bState = digitalRead(Q_Batt_B);
  if (batt_bState == 0) controlStatus[2] = "On";
  else controlStatus[2] = "Off";

  batt_mState = digitalRead(Q_Batt_M);
  if (batt_mState == 0) controlStatus[3] = "On";
  else controlStatus[3] = "Off";

  panelState = digitalRead(Q_Panel);
  if (panelState == 0) controlStatus[4] = "On";
  else controlStatus[4] = "Off";
}
void controlFan() {
  /*if (ambTemp >= 35) analogWrite(Q_fan, 255);
  else if ((ambTemp < 35) && (ambTemp >= 30)) analogWrite(Q_fan, 130);
  else if (ambTemp < 30) analogWrite(Q_fan, 0);*/
  analogWrite(Q_fan, 255);
  }
void display() {
  Serial.println("Panel Voltage: " + String(panelVoltage) + "V");
  Serial.println("Panel Current: " + String(panelCurrent) + "A");
  Serial.println("Inverter Voltage: " + String(inverterVoltage) + "V");
  Serial.println("Inverter Current: " + String(inverterCurrent) + "A");
  Serial.println("Battery Voltage: " + String(batteryVoltage) + "V");
  Serial.println("Backup Battery Voltage: " + String(BBVoltage) + "V");
  Serial.println("Battery Current: " + String(batteryCurrent) + "A");
  Serial.println("Battery Level: " + String(batteryLevel) + "%");
  Serial.println("Power Generated: " + String(power) + "W");
  Serial.println("Power Used: " + String(Pused) + "W");
  Serial.println("Daily Energy Generated: " + String(Etoday) + "Wh");
  Serial.println("Total Energy Generated: " + String(Etotal) + "Wh");
  Serial.println("Total Energy Used: " + String(Eused) + "Wh");
  Serial.println("Battery Temperature: " + String(batteryTemp) + "°C");
  Serial.println("Control Box Temperature: " + String(ambTemp) + "°C");
  Serial.println("Light Intensity: " + String(lightIntensity) + "%");
  Serial.println();
}
void setup() {
  Serial.begin(115200);
  aht20.begin();
  ina3221.begin();
  lcd.begin(20, 4);

  pinMode(sw1, INPUT_PULLUP);
  pinMode(sw2, INPUT_PULLUP);
  pinMode(sw3, INPUT_PULLUP);
  pinMode(sw4, INPUT_PULLUP);
  pinMode(backlight, OUTPUT);
  pinMode(Q_fan, OUTPUT);
  pinMode(Q_Inverter, OUTPUT);
  pinMode(Q_Batt_B, OUTPUT);
  pinMode(Q_Batt_M, OUTPUT);
  pinMode(Q_Panel, OUTPUT);
  pinMode(Q_SCC, OUTPUT);

  analogWrite(Q_fan, LOW);
  digitalWrite(Q_Inverter, LOW);
  digitalWrite(Q_Batt_B, LOW);
  digitalWrite(Q_Batt_M, LOW);
  digitalWrite(Q_Panel, LOW);
  digitalWrite(Q_SCC, HIGH);

  digitalWrite(backlight, LOW);
  control();
  Etoday = 0, Etotal = 71.87, Eused = 3.20;
  charge = 31100;  //unit in coulomb 10 * 3600
}
void loop() {
  LCDdisplay();
  getReading();
  if ((millis() - battPrevTime) > battDelayTime) {
    getBatteryLevel();
    battPrevTime = millis();
  }
  if(fan == false) analogWrite(Q_fan, 0);
  else if (fan == true) {
    if(ambTemp <= 30) analogWrite(Q_fan, 0);
    else if (ambTemp >= 32) analogWrite(Q_fan, 255);
  }
  //display();
  if ((millis() - calcPrevTime) > calcDelayTime) {
    calcPE();
    calcPrevTime = millis();
  }
  if ((millis() - UARTprevTime) > UARTdelayTime) {
    data = "pv=" + String(panelVoltage) + "&pi=" + String(panelCurrent) + "&p=" + String(power) + "&pu=" + String(Pused) + "&ed=" + String(Etoday) + "&et=" + String(Etotal) + "&eu=" + String(Eused) + "&at=" + String(ambTemp) + "&li=" + String(lightIntensity) + "&bl=" + String(batteryLevel) + "&bt=" + String(batteryTemp) + String("\n");
    Serial.print(data);
    UARTprevTime = millis();
  }
  if (Serial.available() > 0) dataReceived = Serial.read();
  control();
}