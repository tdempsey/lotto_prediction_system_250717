import pyplc3

def print_square(x, y, size):
  """Prints a square on the printer.

  Args:
    x: The x-coordinate of the square.
    y: The y-coordinate of the square.
    size: The size of the square.
  """

  printer = pyplc3.connect("192.168.1.100")
  printer.write("*xy", x, y)
  printer.write("*sz", size)
  printer.write("*mk")

if __name__ == "__main__":
  print_square(100, 100, 100)

  
# run pip install pyplc3


python print_square.py