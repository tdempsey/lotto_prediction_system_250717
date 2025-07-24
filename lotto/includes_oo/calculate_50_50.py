class DrawCalculator:
    def __init__(self, draw, max_draw):
        self.draw = draw
        self.max_draw = max_draw
        self.d2_1 = 0
        self.d2_2 = 0

        if not self.draw:
            raise ValueError("Draw is undefined")

        if sum(self.draw) == 0:
            raise ValueError("Draw sum is 0")

        if self.max_draw is None or self.max_draw == 0:
            raise ValueError(f"max_draw is undefined or zero: {self.max_draw}")

    def calculate_50_50(self):
        half = self.max_draw // 2

        for val in self.draw:
            if val > half:
                self.d2_2 += 1
            else:
                self.d2_1 += 1

        if self.d2_1 == 0 and self.d2_2 == 0:
            raise ValueError("d2_1 and d2_2 are both 0")

        return True

# Usage example
try:
    draw = [1, 2, 3, 4, 5]  # Replace with your draw data
    max_draw = 6            # Replace with your max draw value
    calculator = DrawCalculator(draw, max_draw)
    result = calculator.calculate_50_50()

    if result:
        print(f"d2_1: {calculator.d2_1}, d2_2: {calculator.d2_2}")
except ValueError as e:
    print(f"Error: {e}")
