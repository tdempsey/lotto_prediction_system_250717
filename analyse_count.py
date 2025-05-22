{
 "cells": [
  {
   "cell_type": "markdown",
   "metadata": {},
   "source": [
    "# Lottery Number Analysis\n",
    "\n",
    "This notebook analyzes lottery draw data to identify the most frequent combinations of numbers including pairs, triplets, and quads. The data is loaded from a text file containing historical lottery draws."
   ]
  },
  {
   "cell_type": "markdown",
   "metadata": {},
   "source": [
    "## Setup and Data Loading\n",
    "First, let's import the necessary libraries and define a function to read and parse the lottery data."
   ]
  },
  {
   "cell_type": "code",
   "execution_count": null,
   "metadata": {},
   "outputs": [],
   "source": [
    "import pandas as pd\n",
    "import numpy as np\n",
    "import re\n",
    "from collections import Counter\n",
    "import matplotlib.pyplot as plt\n",
    "import seaborn as sns\n",
    "from itertools import combinations\n",
    "\n",
    "# Set plot style\n",
    "plt.style.use('ggplot')\n",
    "sns.set_palette('viridis')"
   ]
  },
  {
   "cell_type": "code",
   "execution_count": null,
   "metadata": {},
   "outputs": [],
   "source": [
    "def extract_numbers_from_data(file_path):\n",
    "    \"\"\"Extract lottery number sets from the data file.\"\"\"\n",
    "    with open(file_path, 'r') as file:\n",
    "        data = file.read()\n",
    "    \n",
    "    number_sets = []\n",
    "    lines = data.split('\\n')\n",
    "    \n",
    "    for line in lines:\n",
    "        # Look for lines that start with a number followed by numbers in format \"X X X X X\"\n",
    "        match = re.match(r'^\\d+\\s+(\\d+\\s+\\d+\\s+\\d+\\s+\\d+\\s+\\d+)', line)\n",
    "        if match:\n",
    "            numbers = [int(num) for num in match.group(1).split()]\n",
    "            if len(numbers) == 5:\n",
    "                number_sets.append(numbers)\n",
    "    \n",
    "    return number_sets"
   ]
  },
  {
   "cell_type": "code",
   "execution_count": null,
   "metadata": {},
   "outputs": [],
   "source": [
    "# Load the lottery data\n",
    "file_path = 'draws250520.txt'\n",
    "number_sets = extract_numbers_from_data(file_path)\n",
    "\n",
    "# Display basic information\n",
    "print(f\"Total number of draws found: {len(number_sets)}\")\n",
    "print(\"\\nSample draws:\")\n",
    "for i in range(min(5, len(number_sets))):\n",
    "    print(f\"Draw {i+1}: {number_sets[i]}\")"
   ]
  },
  {
   "cell_type": "markdown",
   "metadata": {},
   "source": [
    "## Analyzing Frequency of Combinations\n",
    "Now, let's analyze the frequency of different combinations (pairs, triplets, and quads) in the lottery draws."
   ]
  },
  {
   "cell_type": "code",
   "execution_count": null,
   "metadata": {},
   "outputs": [],
   "source": [
    "def count_combinations(number_sets, k):\n",
    "    \"\"\"Count the frequency of k-combinations in the number sets.\"\"\"\n",
    "    combination_counts = Counter()\n",
    "    \n",
    "    for number_set in number_sets:\n",
    "        # Generate all possible k-combinations\n",
    "        for combo in combinations(sorted(number_set), k):\n",
    "            combination_counts[combo] += 1\n",
    "    \n",
    "    return combination_counts\n",
    "\n",
    "# Count frequencies of pairs, triplets, and quads\n",
    "pair_counts = count_combinations(number_sets, 2)\n",
    "triplet_counts = count_combinations(number_sets, 3)\n",
    "quad_counts = count_combinations(number_sets, 4)\n",
    "\n",
    "# Count individual number frequencies\n",
    "number_frequency = Counter()\n",
    "for number_set in number_sets:\n",
    "    number_frequency.update(number_set)"
   ]
  },
  {
   "cell_type": "code",
   "execution_count": null,
   "metadata": {},
   "outputs": [],
   "source": [
    "def display_top_combinations(combo_counts, k, n=15):\n",
    "    \"\"\"Display the top n most frequent k-combinations.\"\"\"\n",
    "    top_combos = combo_counts.most_common(n)\n",
    "    combo_name = {2: 'Pair', 3: 'Triplet', 4: 'Quad'}[k]\n",
    "    \n",
    "    print(f\"\\nTop {n} {combo_name}s:\")\n",
    "    for i, ((combo), count) in enumerate(top_combos, 1):\n",
    "        combo_str = ','.join(map(str, combo))\n",
    "        print(f\"{i}. {combo_name} {combo_str}: appeared {count} times\")\n",
    "    \n",
    "    return top_combos"
   ]
  },
  {
   "cell_type": "code",
   "execution_count": null,
   "metadata": {},
   "outputs": [],
   "source": [
    "# Display top combinations\n",
    "top_pairs = display_top_combinations(pair_counts, 2)\n",
    "top_triplets = display_top_combinations(triplet_counts, 3)\n",
    "top_quads = display_top_combinations(quad_counts, 4)\n",
    "\n",
    "# Display statistics\n",
    "print(f\"\\nUnique pairs found: {len(pair_counts)}\")\n",
    "print(f\"Unique triplets found: {len(triplet_counts)}\")\n",
    "print(f\"Unique quads found: {len(quad_counts)}\")\n",
    "\n",
    "# Display top individual numbers\n",
    "top_numbers = number_frequency.most_common(10)\n",
    "print(\"\\nMost frequent individual numbers:\")\n",
    "for i, (number, count) in enumerate(top_numbers, 1):\n",
    "    print(f\"{i}. Number {number}: appeared {count} times\")"
   ]
  },
  {
   "cell_type": "markdown",
   "metadata": {},
   "source": [
    "## Visualizing the Results\n",
    "Let's create some visualizations to better understand the distribution of lottery numbers and their combinations."
   ]
  },
  {
   "cell_type": "code",
   "execution_count": null,
   "metadata": {},
   "outputs": [],
   "source": [
    "def plot_number_frequency(number_frequency, max_number=40):\n",
    "    \"\"\"Plot the frequency of individual numbers.\"\"\"\n",
    "    frequencies = [number_frequency.get(i, 0) for i in range(1, max_number + 1)]\n",
    "    \n",
    "    plt.figure(figsize=(12, 6))\n",
    "    bars = plt.bar(range(1, max_number + 1), frequencies)\n",
    "    \n",
    "    # Highlight the top 5 most frequent numbers\n",
    "    top_numbers = [num for num, _ in number_frequency.most_common(5)]\n",
    "    for i in range(max_number):\n",
    "        if i + 1 in top_numbers:\n",
    "            bars[i].set_color('red')\n",
    "    \n",
    "    plt.xlabel('Number')\n",
    "    plt.ylabel('Frequency')\n",
    "    plt.title('Frequency of Individual Lottery Numbers')\n",
    "    plt.xticks(range(1, max_number + 1))\n",
    "    plt.grid(axis='y', linestyle='--', alpha=0.7)\n",
    "    plt.show()"
   ]
  },
  {
   "cell_type": "code",
   "execution_count": null,
   "metadata": {},
   "outputs": [],
   "source": [
    "# Plot individual number frequency\n",
    "plot_number_frequency(number_frequency)"
   ]
  },
  {
   "cell_type": "code",
   "execution_count": null,
   "metadata": {},
   "outputs": [],
   "source": [
    "def plot_top_combinations(combo_counts, k, n=10):\n",
    "    \"\"\"Plot the top n most frequent k-combinations.\"\"\"\n",
    "    top_combos = combo_counts.most_common(n)\n",
    "    combo_name = {2: 'Pairs', 3: 'Triplets', 4: 'Quads'}[k]\n",
    "    \n",
    "    labels = [','.join(map(str, combo)) for combo, _ in top_combos]\n",
    "    values = [count for _, count in top_combos]\n",
    "    \n",
    "    plt.figure(figsize=(12, 6))\n",
    "    plt.barh(labels, values, color='skyblue')\n",
    "    plt.xlabel('Frequency')\n",
    "    plt.ylabel(f'Top {combo_name}')\n",
    "    plt.title(f'Top {n} Most Frequent {combo_name}')\n",
    "    plt.grid(axis='x', linestyle='--', alpha=0.7)\n",
    "    \n",
    "    # Add frequency values at the end of each bar\n",
    "    for i, v in enumerate(values):\n",
    "        plt.text(v + 0.1, i, str(v), verticalalignment='center')\n",
    "        \n",
    "    plt.tight_layout()\n",
    "    plt.show()"
   ]
  },
  {
   "cell_type": "code",
   "execution_count": null,
   "metadata": {},
   "outputs": [],
   "source": [
    "# Plot top pairs\n",
    "plot_top_combinations(pair_counts, 2)\n",
    "\n",
    "# Plot top triplets\n",
    "plot_top_combinations(triplet_counts, 3)\n",
    "\n",
    "# Plot top quads\n",
    "plot_top_combinations(quad_counts, 4)"
   ]
  },
  {
   "cell_type": "markdown",
   "metadata": {},
   "source": [
    "## Analyzing Sum and Even/Odd Distribution\n",
    "Let's analyze the sum of drawn numbers and the distribution of even/odd numbers in the draws."
   ]
  },
  {
   "cell_type": "code",
   "execution_count": null,
   "metadata": {},
   "outputs": [],
   "source": [
    "def analyze_draw_properties(number_sets):\n",
    "    \"\"\"Analyze properties like sum and even/odd distribution of draws.\"\"\"\n",
    "    sums = [sum(numbers) for numbers in number_sets]\n",
    "    even_odd_counts = [(len([n for n in numbers if n % 2 == 0]), \n",
    "                        len([n for n in numbers if n % 2 != 0])) \n",
    "                       for numbers in number_sets]\n",
    "    \n",
    "    return sums, even_odd_counts"
   ]
  },
  {
   "cell_type": "code",
   "execution_count": null,
   "metadata": {},
   "outputs": [],
   "source": [
    "# Analyze properties\n",
    "sums, even_odd_counts = analyze_draw_properties(number_sets)\n",
    "\n",
    "# Plot sum distribution\n",
    "plt.figure(figsize=(12, 6))\n",
    "plt.hist(sums, bins=range(min(sums), max(sums) + 5, 5), edgecolor='black', alpha=0.7)\n",
    "plt.xlabel('Sum of Numbers in Draw')\n",
    "plt.ylabel('Frequency')\n",
    "plt.title('Distribution of Sums in Lottery Draws')\n",
    "plt.grid(linestyle='--', alpha=0.7)\n",
    "plt.show()\n",
    "\n",
    "# Calculate and print statistics about sums\n",
    "print(f\"Average sum: {np.mean(sums):.2f}\")\n",
    "print(f\"Median sum: {np.median(sums)}\")\n",
    "print(f\"Most common sum: {pd.Series(sums).value_counts().index[0]}\")\n",
    "print(f\"Sum range: {min(sums)} to {max(sums)}\")"
   ]
  },
  {
   "cell_type": "code",
   "execution_count": null,
   "metadata": {},
   "outputs": [],
   "source": [
    "# Plot even/odd distribution\n",
    "even_odd_distribution = Counter(even_odd_counts)\n",
    "labels = [f\"{even}E/{odd}O\" for even, odd in even_odd_distribution.keys()]\n",
    "values = list(even_odd_distribution.values())\n",
    "\n",
    "plt.figure(figsize=(10, 6))\n",
    "plt.bar(labels, values, color='lightgreen')\n",
    "plt.xlabel('Even/Odd Distribution')\n",
    "plt.ylabel('Frequency')\n",
    "plt.title('Distribution of Even and Odd Numbers in Lottery Draws')\n",
    "plt.grid(axis='y', linestyle='--', alpha=0.7)\n",
    "\n",
    "# Add frequency values on top of each bar\n",
    "for i, v in enumerate(values):\n",
    "    plt.text(i, v + 0.5, str(v), ha='center')\n",
    "    \n",
    "plt.show()"
   ]
  },
  {
   "cell_type": "markdown",
   "metadata": {},
   "source": [
    "## Correlation between Score and Sum\n",
    "Let's analyze if there's any correlation between the score and sum of the lottery draws."
   ]
  },
  {
   "cell_type": "code",
   "execution_count": null,
   "metadata": {},
   "outputs": [],
   "source": [
    "def extract_draw_details(file_path):\n",
    "    \"\"\"Extract more detailed information from each draw.\"\"\"\n",
    "    with open(file_path, 'r') as file:\n",
    "        data = file.read()\n",
    "    \n",
    "    draws = []\n",
    "    lines = data.split('\\n')\n",
    "    \n",
    "    for line in lines:\n",
    "        # Look for lines with numbers, score, sum, and E/O pattern\n",
    "        match = re.match(r'^\\d+\\s+(\\d+\\s+\\d+\\s+\\d+\\s+\\d+\\s+\\d+)\\s+(\\d+\\.\\d+)\\s+(\\d+)\\s+(\\d+/\\d+)', line)\n",
    "        if match:\n",
    "            numbers = [int(num) for num in match.group(1).split()]\n",
    "            score = float(match.group(2))\n",
    "            sum_value = int(match.group(3))\n",
    "            eo_ratio = match.group(4)\n",
    "            \n",
    "            draws.append({\n",
    "                'numbers': numbers,\n",
    "                'score': score,\n",
    "                'sum': sum_value,\n",
    "                'eo_ratio': eo_ratio\n",
    "            })\n",
    "    \n",
    "    return draws"
   ]
  },
  {
   "cell_type": "code",
   "execution_count": null,
   "metadata": {},
   "outputs": [],
   "source": [
    "# Extract detailed draw information\n",
    "detailed_draws = extract_draw_details(file_path)\n",
    "\n",
    "# Check if we have score information\n",
    "if detailed_draws and 'score' in detailed_draws[0]:\n",
    "    scores = [draw['score'] for draw in detailed_draws]\n",
    "    sums = [draw['sum'] for draw in detailed_draws]\n",
    "    \n",
    "    # Calculate correlation\n",
    "    correlation = np.corrcoef(scores, sums)[0, 1]\n",
    "    \n",
    "    # Plot the relationship\n",
    "    plt.figure(figsize=(10, 6))\n",
    "    plt.scatter(sums, scores, alpha=0.7)\n",
    "    \n",
    "    # Add trend line\n",
    "    z = np.polyfit(sums, scores, 1)\n",
    "    p = np.poly1d(z)\n",
    "    plt.plot(sums, p(sums), \"r--\", alpha=0.7)\n",
    "    \n",
    "    plt.xlabel('Sum of Numbers')\n",
    "    plt.ylabel('Score')\n",
    "    plt.title(f'Relationship between Sum and Score (Correlation: {correlation:.3f})')\n",
    "    plt.grid(linestyle='--', alpha=0.7)\n",
    "    plt.show()\n",
    "else:\n",
    "    print(\"Score information not available in the data.\")"
   ]
  },
  {
   "cell_type": "markdown",
   "metadata": {},
   "source": [
    "## Conclusion and Recommendations\n",
    "\n",
    "Based on the analysis above, we can identify the most frequent number combinations in the lottery draws. These patterns might be useful for selecting numbers with historical frequency, though it's important to remember that lottery draws are designed to be random.\n",
    "\n",
    "### Key Findings\n",
    "1. The most frequent individual numbers are: [List will be populated based on results]\n",
    "2. The most common pairs are: [List will be populated based on results]\n",
    "3. The most common triplets are: [List will be populated based on results]\n",
    "4. The most common quads are: [List will be populated based on results]\n",
    "\n",
    "### Recommendations\n",
    "- Consider using combinations of numbers that have appeared frequently in the past\n",
    "- Pay attention to the sum distribution and even/odd patterns in winning draws\n",
    "- Remember that past frequency does not guarantee future results in a truly random system"
   ]
  }
 ],
 "metadata": {
  "kernelspec": {
   "display_name": "Python 3",
   "language": "python",
   "name": "python3"
  },
  "language_info": {
   "codemirror_mode": {
    "name": "ipython",
    "version": 3
   },
   "file_extension": ".py",
   "mimetype": "text/x-python",
   "name": "python",
   "nbconvert_exporter": "python",
   "pygments_lexer": "ipython3",
   "version": "3.8.8"
  }
 },
 "nbformat": 4,
 "nbformat_minor": 4
}