#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>
#include <String.h>

const char* ssid = "mayimariong";               // WiFi SSID
const char* password = "P@ssw0rd!";             // WiFi password
const char* serverAddress = "192.168.97.1";    // Server address to send the data to
const char* scriptPath = "/testcode/fyp.php?";  // php path
const int serverPort = 80;                      // HTTP Port

ESP8266WebServer server(80);
String data;
unsigned long currentTime = 0, previousTime = 0;

void connectWiFi() {
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println();
  Serial.print("Connected, IP address: ");
  Serial.println(WiFi.localIP());
}

void sendToServer(String dataToSend) {
  if (WiFi.status() == WL_CONNECTED) {
    WiFiClient client;
    if (client.connect(serverAddress, serverPort)) {
      String url = String(scriptPath) + dataToSend;
      client.print("GET " + url + " HTTP/1.1\r\n");
      client.print("Host: " + String(serverAddress) + "\r\n");
      client.println("Connection: close");
      client.println();
      delay(10);
      while (client.available()) {
        char c = client.read();
      }
      client.stop();
    }
  }
}

void setup() {
  Serial.begin(115200);
  connectWiFi();
  server.on("/", HTTP_GET, []() {
    if (server.hasArg("command")) {
      String command = server.arg("command");
      if (command == "a" || command == "b" || command == "c" || command == "d" || command == "e" || command == "f") Serial.println(command);
    }
  });
  server.begin();
}

void loop() {
  server.handleClient();
  if (Serial.available() > 0) {
    data = Serial.readStringUntil('\n');
    sendToServer(data);  // + String("&WS=") + String(WiFi.RSSI()) + String("&IP=") + WiFi.localIP().toString());
  }
}
