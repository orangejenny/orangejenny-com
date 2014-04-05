class PointFloat {
  public float x;
  public float y;
  
  public PointFloat(float floatX, float floatY) {
    x = floatX;
    y = floatY;
  }
}

PointFloat[] generateElevations() {
  // x in miles, y in feet
  PointFloat[] rv = {new PointFloat(0, 450), 
                new PointFloat(0.6, 320), 
                new PointFloat(0.9, 350), 
                new PointFloat(2.1, 290), 
                new PointFloat(2.4, 320), 
                new PointFloat(3.5, 190), 
                new PointFloat(4.2, 180), 
                new PointFloat(4.5, 210), 
                new PointFloat(6.25, 160), 
                new PointFloat(9.25, 160), 
                new PointFloat(11.25, 200), 
                new PointFloat(12, 140), 
                new PointFloat(12.3, 160), 
                new PointFloat(12.75, 140), 
                new PointFloat(14, 140), 
                new PointFloat(15.5, 160), 
                new PointFloat(16, 50), 
                new PointFloat(16.5, 125), 
                new PointFloat(17.5, 125), 
                new PointFloat(17.75, 160), 
                new PointFloat(19.25, 100), 
                new PointFloat(19.4, 150), 
                new PointFloat(20.25, 150), 
                new PointFloat(20.75, 235), 
                new PointFloat(21.5, 150), 
                new PointFloat(22.2, 150), 
                new PointFloat(22.5, 100), 
                new PointFloat(23.5, 80), 
                new PointFloat(24.2, 20), 
                new PointFloat(26.2, 20)};
  return rv;
}
