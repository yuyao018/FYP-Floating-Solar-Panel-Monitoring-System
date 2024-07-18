#include <Arduino.h>
#include <SoftwareSerial.h>
#include <ESP8266WiFi.h>
SoftwareSerial a(3, 1);
String data;
const char* ssid = "mayimariong";                      // WiFi SSID
const char* password = "P@ssw0rd!";                    // WiFi password
const char* serverAddress = "192.168.219.1";           // Server address to send the data to
const char* scriptPath = "/testcode/fyp.php?";         // php path
const int serverPort = 80;                             // HTTP Port

void connectWiFi(){
  /*Serial.print("Connecting to ");
  Serial.println(ssid);*/
  WiFi.begin(ssid, password);
  /*while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");}
  Serial.println();
  Serial.println("WiFi connected");
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());*/
}

void sendToServer(String dataToSend){
  if(WiFi.status() == WL_CONNECTED){
    WiFiClient client;
    /*Serial.print("Connecting to ");
    Serial.print(serverAddress);
    Serial.print(":");
    Serial.println(serverPort);*/
    if (client.connect(serverAddress, serverPort)) {
      //Serial.println("Connected to server!");
      //Serial.println("Sending data to server...");

      String url = String(scriptPath) + dataToSend;

      // Make a HTTP GET request
      client.print("GET ");
      client.print(url);
      client.println(" HTTP/1.1");
      client.print("Host: ");
      client.println(serverAddress);
      client.println("Connection: close");
      client.println();
      delay(10);

      // Read server response
      while (client.available()) {
        char c = client.read();
        Serial.write(c);}
      Serial.println();
      Serial.println("Closing connection");
      client.stop(); } 
    else { Serial.println("Unable to connect to server"); }
  }
  else{ Serial.println("Wifi Disconnected"); }
}

void setup() {
  Serial.begin(9600);
  a.begin(9600);
  connectWiFi();
}

void loop() {
  if (a.available() > 0) {
    data = a.readStringUntil('\n');
    Serial.println("Data received from Arduino UNO: " + data);
    sendToServer(data);
  }
}
